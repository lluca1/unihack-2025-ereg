using UnityEngine;
using System;

public class Exhibit : MonoBehaviour
{
    [SerializeField] private string exhibitId;
    [SerializeField] private float desiredModelSize = 3f;
    [SerializeField] private Vector3 standOffset;

    private void Setup(GameObject model)
    {
        if (model == null)
        {
            Debug.LogError($"Setup failed: Model data was null for {exhibitId}");
            return;
        }

        GameObject instance = Instantiate(model, transform);
        Destroy(model);

        ModelUtility.CenterPivot(instance);

        for (int i = 0; i < instance.transform.childCount; i++)
        {
            instance.transform.GetChild(i).localPosition = Vector3.zero;
        }

        ModelUtility.ScaleToTargetSize(instance, desiredModelSize);

        instance.transform.localPosition += standOffset;

        Debug.Log($"Exhibit {exhibitId} setup complete.");
    }

    public void LoadData(string expoId, string exhibitId)
    {
        this.exhibitId = exhibitId;

        GameManager.Instance.ModelLoader.Load(Setup);
    }
}