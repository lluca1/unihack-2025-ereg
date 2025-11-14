using UnityEngine;

public class ExpoTile : MonoBehaviour
{
    [SerializeField] private GameObject floor, ceiling, wallL, wallR, stand, lampL, lampR;

    public float GetSize() => transform.localScale.x * 10;
}
