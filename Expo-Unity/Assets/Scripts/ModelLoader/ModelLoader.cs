using UnityEngine;
using UnityEngine.Networking;
using System.Collections;
using System.IO;
using Dummiesman;
using System;
using System.Text;
using System.Text.RegularExpressions; // Required for parsing the MTL file

public class ModelLoader : MonoBehaviour
{
    // The Base URL points to the directory containing the OBJ, MTL, and JPG files
    private const string BASE_URL = "https://raw.githubusercontent.com/ladybug-tools/3d-models/master/obj/vehicles/";
    private const string OBJ_EXT = "Car.obj"; // Modified to use the full name
    private const string MTL_EXT = "Car.mtl"; // Modified to use the full name
    private const string OBJ_FILENAME = "Car"; // Used for logging

    private static ModelLoader instance;

    private void Awake()
    {
        // Singleton pattern implementation
        if (instance == null)
        {
            instance = this;
        }
        else
        {
            Destroy(gameObject);
        }
    }

    // Since we're hardcoding the model URL for this example, the Load method is simplified.
    // In a real application, you would still use expoId/exhibitId to determine the paths.
    public void Load(Action<GameObject> onLoadedModel)
    {
        StartCoroutine(LoadModelFromURL(onLoadedModel));
    }

    private IEnumerator DownloadTexture(string textureFileName, Action<Texture2D> onTextureLoaded)
    {
        string textureUrl = BASE_URL + textureFileName;

        // Use UnityWebRequestTexture to download the image data directly into a Texture2D object
        using (UnityWebRequest uwr = UnityWebRequestTexture.GetTexture(textureUrl))
        {
            yield return uwr.SendWebRequest();

            if (uwr.result == UnityWebRequest.Result.Success)
            {
                // Retrieve the downloaded Texture2D
                Texture2D texture = DownloadHandlerTexture.GetContent(uwr);
                onTextureLoaded?.Invoke(texture);
                Debug.Log($"Successfully downloaded texture: {textureFileName}");
            }
            else
            {
                Debug.LogError($"Error downloading texture ({textureUrl}): {uwr.error}");
                onTextureLoaded?.Invoke(null);
            }
        }
    }

    private string ExtractTextureFileName(string mtlContent)
    {
        // Use a regular expression to find the 'map_Kd' line
        // It looks for 'map_Kd' followed by one or more whitespace characters (\s+)
        // and captures the rest of the line (the filename)
        Match match = Regex.Match(mtlContent, @"map_Kd\s+(.+)\s*");
        if (match.Success && match.Groups.Count > 1)
        {
            // Group 1 contains the captured filename
            return match.Groups[1].Value.Trim();
        }
        return null; // Return null if the texture path is not found
    }

    private IEnumerator LoadModelFromURL(Action<GameObject> onLoadedModel)
    {
        string objUrl = BASE_URL + OBJ_EXT;
        string mtlUrl = BASE_URL + MTL_EXT;
        string mtlContent = null;
        Texture2D downloadedTexture = null;

        // --- 1. Download the MTL File ---
        using (UnityWebRequest mtlUwr = UnityWebRequest.Get(mtlUrl))
        {
            yield return mtlUwr.SendWebRequest();
            if (mtlUwr.result == UnityWebRequest.Result.Success)
            {
                mtlContent = mtlUwr.downloadHandler.text;
                Debug.Log($"Successfully downloaded MTL content for: {OBJ_FILENAME}");
            }
        }

        // --- 2. Extract Texture Name and Download Texture ---
        if (!string.IsNullOrEmpty(mtlContent))
        {
            string textureFileName = ExtractTextureFileName(mtlContent);

            if (!string.IsNullOrEmpty(textureFileName))
            {
                // This yields until the texture download is complete
                yield return DownloadTexture(textureFileName, tex => downloadedTexture = tex);
            }
        }


        // --- 3. Download the OBJ File and Load Model ---
        using (UnityWebRequest objUwr = UnityWebRequest.Get(objUrl))
        {
            yield return objUwr.SendWebRequest();

            if (objUwr.result != UnityWebRequest.Result.Success)
            {
                Debug.LogError($"Error downloading OBJ file ({objUrl}): {objUwr.error}");
                onLoadedModel?.Invoke(null);
                yield break;
            }

            try
            {
                byte[] objData = objUwr.downloadHandler.data;
                using (MemoryStream objStream = new MemoryStream(objData))
                {
                    GameObject loadedModel;

                    // Load the model using the OBJ and MTL data streams
                    if (!string.IsNullOrEmpty(mtlContent))
                    {
                        byte[] mtlBytes = Encoding.UTF8.GetBytes(mtlContent);
                        using (MemoryStream mtlStream = new MemoryStream(mtlBytes))
                        {
                            loadedModel = new OBJLoader().Load(objStream, mtlStream);
                        }
                    }
                    else
                    {
                        loadedModel = new OBJLoader().Load(objStream);
                    }

                    // --- 4. Apply Downloaded Texture (if available) ---
                    if (loadedModel != null && downloadedTexture != null)
                    {
                        // OBJLoader often creates a new material for the model. 
                        // We need to find the material and set its main texture.
                        Renderer renderer = loadedModel.GetComponentInChildren<Renderer>();
                        if (renderer != null)
                        {
                            // Assign the downloaded texture to the material's main texture property
                            renderer.material.mainTexture = downloadedTexture;
                            Debug.Log($"Successfully applied texture to material.");
                        }
                        else
                        {
                            Debug.LogWarning("Could not find Renderer component on the loaded model to apply texture.");
                        }
                    }

                    onLoadedModel?.Invoke(loadedModel);
                    Debug.Log($"Successfully completed loading process for: {OBJ_FILENAME}");
                }
            }
            catch (System.Exception e)
            {
                Debug.LogError($"OBJ Importer Error on {OBJ_FILENAME}: {e.Message}");
                onLoadedModel?.Invoke(null);
            }
        }
    }
}