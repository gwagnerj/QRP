Sub ChangeRef()

' this sub just changes the word reference and saves the workbook to reflect this new name it does not manipulate the
' word document in any way

Dim WordTempLoc As FileDialog
Dim Suggest_WBname As String
Dim Workbook_name As Variant

Set WordTempLoc = Application.FileDialog(msoFileDialogFilePicker)

With WordTempLoc
    
    .Title = "Select Word problem statement file with ##variables##"
    .Filters.Add "Word Type Files", "*.docx,*.doc", 1
    If .Show <> -1 Then GoTo NoSelection
    Worksheets("Problem").Range("Word_File").Value = Dir(.SelectedItems(1)) 'Document Name
    Worksheets("Problem").Range("Word_Path").Value = .SelectedItems(1) 'Document Pathway
End With


' the above was modified from Randy Austin and is ExcelFreeLancersGroup


With Worksheets("Problem")
  
      If .Range("Word_Path").Value = Empty Then
        MsgBox "No Word document in Word_Path"
        .Range("Word_Path").Select
        Exit Sub
      End If
    
    'advance the current step
     Worksheets("Problem").Range("Current_Step").Value = 2
     
    Suggest_WBname = Replace(Worksheets("Problem").Range("Word_File").Value, ".docx", "")
    Workbook_name = Application.GetSaveAsFilename(InitialFileName:=Suggest_WBname, FileFilter:="Excel Macro-Enabled Workbook,*.xlsm")
    
        If Workbook_name <> False Then
                ActiveWorkbook.SaveAs Filename:=Workbook_name, FileFormat:=52, CreateBackup:=False
        End If

End With
NoSelection:
End Sub
