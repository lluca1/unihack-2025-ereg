using TMPro;
using UnityEngine;
using UnityEngine.UI;

public class ExpoLoadMenu : MonoBehaviour
{
    [SerializeField] private TMP_InputField idField;
    [SerializeField] private Button loadButton;

    private void OnEnable()
    {
        loadButton.onClick.AddListener(OnLoadClick);
    }

    private void OnLoadClick()
    {
        string expoId = idField.text;
        GameManager.Instance.ExpoManager.LoadExpo(expoId);
    }
}
