Sub QRAddWordFiles()
Dim WordTempLoc As FileDialog
Dim FirstRow As Long

Set WordTempLoc = Application.FileDialog(msoFileDialogFilePicker)
FirstRow = Sheet2.Range("E99999").End(xlUp).Row + 1 'First Available Row
With WordTempLoc
    .Title = "Select Word file to attach"
    .Filters.Add "Word Type Files", "*.docx,*.doc", 1
    If .Show <> -1 Then GoTo NoSelection
    Sheet2.Range("E" & FirstRow).Value = Dir(.SelectedItems(1)) 'Document Name
    Sheet2.Range("F" & FirstRow).Value = .SelectedItems(1) 'Document Pathway
End With
NoSelection:
End Sub
' the above is modified from the custom Letter creator by Randy Austin of ExcelFreelancersGroup