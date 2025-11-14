using UnityEngine;

public class ExpoTile : MonoBehaviour
{
    [SerializeField] private Renderer floor, ceiling, wallL, wallR;
    [SerializeField] private Transform presetModelsParent;

    private Material floorTexture, ceilingTexture, wallTexture;

    public float GetSize() => transform.localScale.x * 10;

    private void Setup()
    {
        floor.material = floorTexture;
        ceiling.material = ceilingTexture;
        wallL.material = wallTexture;
        wallR.material = wallTexture;
    }

    public void LoadData(string id)
    {
        // load from database
    }

    public void LoadData(ExpoPreset preset, int index)
    {
        floorTexture = preset.floorTexture;
        ceilingTexture = preset.ceilingTexture;
        wallTexture = preset.wallTexture;

        presetModelsParent.GetChild(index).gameObject.SetActive(true);

        Setup();
    }
}
