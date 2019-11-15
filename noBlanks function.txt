Function NoBlanks(RR As Range) As Variant
    Dim Arr() As Variant
    Dim R As Range
    Dim N As Long
    Dim L As Long
    If RR.Rows.Count > 1 And RR.Columns.Count > 1 Then
        NoBlanks = CVErr(xlErrRef)
        Exit Function
    End If
    
    If Application.Caller.Cells.Count > RR.Cells.Count Then
        N = Application.Caller.Cells.Count
    Else
        N = RR.Cells.Count
    End If
    
    ReDim Arr(1 To N)
    N = 0
    For Each R In RR.Cells
        If Len(R.Value) > 0 Then
            N = N + 1
            Arr(N) = R.Value
        End If
    Next R
    For L = N + 1 To UBound(Arr)
        Arr(L) = vbNullString
    Next L
    ReDim Preserve Arr(1 To L)
    If Application.Caller.Rows.Count > 1 Then
        NoBlanks = Application.Transpose(Arr)
    Else
        NoBlanks = Arr
    End If
End Function
