using UnityEngine;
using UnityEngine.Networking;
using System.Collections;
using System.IO;
using Dummiesman;
using System;
using static System.Net.WebRequestMethods;

public class DatabaseLoader : MonoBehaviour
{
    private const string BASE_URL = "https://openxzbt.com/";
    private const string OBJ_EXT = ".obj";

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

    public void LoadExhibit(string expoId, string exhibitId, Action<GameObject> onLoadedModel)
    {
        StartCoroutine(LoadModelFromURL(expoId, exhibitId, onLoadedModel));
    }

    private IEnumerator LoadModelFromURL(string expoId, string exhibitId, Action<GameObject> onLoadedModel)
    {
        //string objUrl = BASE_URL + "api/" + expoId + "/" + exhibitId + OBJ_EXT;
        string objUrl = "https://cdn.jsdelivr.net/gh/mrdoob/three.js/examples/models/obj/male02/male02.obj";

        using (UnityWebRequest uwr = UnityWebRequest.Get(objUrl))
        {
            yield return uwr.SendWebRequest();

            if (uwr.result != UnityWebRequest.Result.Success)
            {
                Debug.LogError($"Error downloading OBJ file ({objUrl}): {uwr.error}");
                onLoadedModel?.Invoke(null); // IMPORTANT: Invoke callback with null on failure
                yield break;
            }

            try
            {
                byte[] objData = uwr.downloadHandler.data;
                MemoryStream stream = new MemoryStream(objData);

                GameObject loadedModel = new OBJLoader().Load(stream);

                // Pass the temporary GameObject to the callback
                onLoadedModel?.Invoke(loadedModel);

                Debug.Log($"Successfully loaded model data for: {exhibitId}");
            }
            catch (System.Exception e)
            {
                Debug.LogError($"OBJ Importer Error on {exhibitId}: {e.Message}");
                onLoadedModel?.Invoke(null); // Invoke callback with null on exception
            }
        }
    }
}