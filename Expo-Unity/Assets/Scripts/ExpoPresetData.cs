using System;
using UnityEngine;

[Serializable]
public struct ExpoPreset
{
    public string name;
    public Texture floorTexture, ceilingTexture, wallTexture;
    public Mesh stand, lamp;
}

[CreateAssetMenu(fileName = "Expo Presets Settings", menuName = "Expo Data/Expo Presets Settings")]
public class ExpoPresetData : ScriptableObject
{
    [SerializeField] private ExpoPreset[] presets;
}
