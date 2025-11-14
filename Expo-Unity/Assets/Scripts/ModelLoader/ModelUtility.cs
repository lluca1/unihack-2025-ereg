using UnityEngine;

public class ModelUtility : MonoBehaviour
{
    /// <summary>
    /// Scales the given GameObject uniformly so that its largest dimension 
    /// (X, Y, or Z) matches the targetSize.
    /// </summary>
    /// <param name="modelInstance">The already instantiated model to scale.</param>
    /// <param name="targetSize">The desired maximum size (e.g., 2.0f units).</param>
    /// <returns>True if scaling was successful, false otherwise.</returns>
    public static bool ScaleToTargetSize(GameObject modelInstance, float targetSize)
    {
        if (modelInstance == null || targetSize <= 0)
        {
            Debug.LogError("ModelScaler: Invalid input model or target size.");
            return false;
        }

        // Get all Renderer components in the model and its children
        Renderer[] renderers = modelInstance.GetComponentsInChildren<Renderer>();

        if (renderers.Length == 0)
        {
            Debug.LogWarning($"ModelScaler: Model '{modelInstance.name}' has no Renderer components for size calculation.");
            // If there's no renderer, we can't calculate bounds, so we exit.
            return false;
        }

        // 1. Calculate the combined bounding box of all parts
        Bounds combinedBounds = renderers[0].bounds;
        for (int i = 1; i < renderers.Length; i++)
        {
            // Encapsulate combines the bounds into one large box
            combinedBounds.Encapsulate(renderers[i].bounds);
        }

        // 2. Find the largest dimension (width, height, or depth)
        // We use the size property of the bounds, which is world-space.
        float maxDimension = Mathf.Max(combinedBounds.size.x, combinedBounds.size.y, combinedBounds.size.z);

        // 3. Calculate the uniform scale factor
        float scaleFactor = targetSize / maxDimension;

        // 4. Apply the uniform scale to the root object's local scale
        modelInstance.transform.localScale *= scaleFactor;

        Debug.Log($"ModelScaler: '{modelInstance.name}' scaled from {maxDimension:F2} to {targetSize:F2} (Factor: {scaleFactor:F4}).");

        return true;
    }

    public static GameObject CenterPivot(GameObject originalModel)
    {
        if (originalModel == null) return null;

        // --- FIX: Search children for the MeshFilter ---
        MeshFilter mf = originalModel.GetComponentInChildren<MeshFilter>();

        if (mf == null || mf.sharedMesh == null)
        {
            // The original error message is now accurate if NO MeshFilter is found anywhere.
            Debug.LogError("Cannot center pivot: Model does not have a valid MeshFilter on itself OR its children.");
            return originalModel;
        }

        // Use the mesh from the child object
        Mesh mesh = mf.sharedMesh;

        // 1. Calculate the center of the mesh bounds
        // NOTE: This center is in the child's local space relative to the model's pivot
        Vector3 center = mesh.bounds.center;

        // 2. The rest of the logic proceeds by shifting the mesh vertices relative 
        //    to the child object's local space.
        Vector3 offset = -center;

        // Move the mesh's vertex data (this changes the mesh asset)
        Vector3[] vertices = mesh.vertices;
        for (int i = 0; i < vertices.Length; i++)
        {
            vertices[i] += offset;
        }
        mesh.vertices = vertices;

        // Recalculate properties
        mesh.RecalculateBounds();
        mesh.RecalculateNormals();

        // 3. To compensate for the vertex shift and keep the visual object in the same
        //    world position, you must shift the child object's local position by the original center.
        mf.transform.localPosition = center;

        Debug.Log($"Pivot centered for {originalModel.name}. Center shifted by: {center}");

        return originalModel;
    }
}
