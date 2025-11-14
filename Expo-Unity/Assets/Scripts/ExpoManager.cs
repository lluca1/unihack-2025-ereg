using System.Collections.Generic;
using UnityEngine;
using UnityEngine.Experimental.GlobalIllumination;
using UnityEngine.SceneManagement;

public class ExpoManager : MonoBehaviour
{
    [SerializeField] private ExpoPresetData presetsData;

    [SerializeField] private FirstPersonController playerPrefab;
    [SerializeField] private ExpoTile tilePrefab;
    [SerializeField] private Exhibit exhibitPrefab;

    [SerializeField] private Vector3 playerSpawnOffset;
    [SerializeField] private Vector3 tileSpawnOffset;

    [Header("Debug")]
    [SerializeField] private string currentExpoId;

    [SerializeField] private bool usePresetSettings = true;
    [SerializeField] private int presetIndex; 
    [SerializeField] private List<string> exhibitIds = new(); // loaded from database

    [SerializeField] private List<ExpoTile> createdTiles = new();
    [SerializeField] private List<Exhibit> createdExhibits = new();


    private static ExpoManager instance;

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

    private void OnEnable()
    {
        SceneManager.sceneLoaded += OnSceneLoaded;
    }

    private void OnDisable()
    {
        SceneManager.sceneLoaded -= OnSceneLoaded;
    }

    private void OnSceneLoaded(Scene scene, LoadSceneMode loadScene)
    {
        if (scene.buildIndex == SceneLoader.SCENE_INDEX_EXPO)
        {
            CreateExpo();
        }
    }

    private void CreateExpo()
    {
        // load exhibit ids from database where expo id == currentExpoId

        if (usePresetSettings)
        {
            Light sun = FindFirstObjectByType<Light>();
            sun.color = presetsData.Presets[presetIndex].sunColor;
        }

        for (int i = 0; i < exhibitIds.Count; i++)
        {
            Vector3 pos = tileSpawnOffset + new Vector3(0, 0, createdTiles.Count * tilePrefab.GetSize());

            ExpoTile tile = Instantiate(tilePrefab, pos, Quaternion.identity);

            if (usePresetSettings)
            {
                tile.LoadData(presetsData.Presets[presetIndex], presetIndex);
            }
            else
            {
                tile.LoadData(currentExpoId);
            }

            createdTiles.Add(tile);

            Exhibit exhibit = Instantiate(exhibitPrefab, tile.transform.position, Quaternion.identity);

            exhibit.LoadData(currentExpoId ,exhibitIds[i]);

            createdExhibits.Add(exhibit);
        }

        Instantiate(playerPrefab, playerSpawnOffset, Quaternion.identity);
    }

    public void LoadExpo(string id)
    {
        currentExpoId = id;
        GameManager.Instance.SceneLoader.LoadExpoScene();
    }
}
