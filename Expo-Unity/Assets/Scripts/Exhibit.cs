using UnityEngine;

public class Exhibit : MonoBehaviour
{
    private string id;
    private Mesh mesh;
    private Vector3 pos;
    private Vector3 scale;

    private void Setup()
    {
        transform.localPosition += pos;
        transform.localScale = scale;
    }

    public void LoadData(string id)
    {
        this.id = id;

        // Load from database
        pos = new Vector3(0, 3, 0);
        scale = Vector3.one;

        Setup();
    }
}
