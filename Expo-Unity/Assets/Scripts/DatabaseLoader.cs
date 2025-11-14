using System.Collections;
using UnityEngine;
using UnityEngine.Networking;

public class DatabaseLoader : MonoBehaviour
{
    private const string BASE_URL = "openxzbt.com";

    private static DatabaseLoader instance;

    private void Awake()
    {
        if (instance == null)
        {
            instance = this;
        }
        else
        {
            Destroy(gameObject);
        }
    }

    public void Load(string expoId, string exhibitId)
    {
        // Now expecting an OBJ file
        StartCoroutine(LoadObjModel(expoId, exhibitId));
    }

    private IEnumerator LoadObjModel(string expoId, string exhibitId)
    {
        string objUrl = BASE_URL + "/api/" + expoId + "/" + exhibitId + ".obj";

        using (UnityWebRequest objUWR = UnityWebRequest.Get(objUrl))
        {
            yield return objUWR.SendWebRequest();

            if (objUWR.result != UnityWebRequest.Result.Success)
            {
                Debug.LogError("Error downloading OBJ file: " + objUWR.error);
                yield break;
            }

            // Get the OBJ file contents as text
            string objText = objUWR.downloadHandler.text;

            // 2. Load the Model using the Importer Library
            // The library will take the OBJ text, parse it, and construct a new GameObject.

            // Example of a hypothetical parsing call:
            // GameObject loadedModel = ObjImporter.Parse(objText);

            // You would also need to implement logic to download and pass the .mtl file 
            // (material data) and any associated textures to the parser.

            // Example instantiation:
            // if (loadedModel != null)
            // {
            //     Instantiate(loadedModel, Vector3.zero, Quaternion.identity);
            //     Debug.Log($"Successfully loaded OBJ model.");
            // }
            // else
            // {
            //     Debug.LogError("Failed to parse OBJ data.");
            // }
        }
    }
}
