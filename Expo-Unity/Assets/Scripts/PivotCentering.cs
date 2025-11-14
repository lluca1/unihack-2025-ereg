using UnityEngine;
using System.Collections.Generic;

public static class PivotCentering
{
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