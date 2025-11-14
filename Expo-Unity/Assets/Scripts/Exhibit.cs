using UnityEngine;

public class Exhibit : MonoBehaviour
{
    private string id; // [EXPO ID]_[EXHIBIT ID]
    private Mesh mesh;
    private Vector3 pos;
    private Vector3 scale;

    public void LoadData(string id)
    {
        this.id = id;

        // Load and set parameters from database based on id
        pos = new Vector3(0, 1, 0);
        scale = Vector3.one;

        transform.localPosition += pos;
        transform.localScale = scale;
    }
}
