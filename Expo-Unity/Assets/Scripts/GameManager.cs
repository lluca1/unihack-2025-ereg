using UnityEngine;

public class GameManager : MonoBehaviour
{
    public bool IsPaused { get; private set; }

    public static GameManager Instance;

    private void Awake()
    {
        if (Instance == null)
        {
            Instance = this;
        }
        else
        {
            Destroy(gameObject);
        }
    }
}
