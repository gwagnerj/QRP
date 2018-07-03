Sub saveTableToCSV()

    Dim tbl As ListObject
    Dim csvFilePath As String
    Dim fNum, i As Integer
    Dim tblArr
    Dim rowArr
    Dim csvVal
    Dim TableExists As Boolean
    


If ThisWorkbook.Worksheets("Problem").Range("Current_Step").Value < 3 Then
    MsgBox "According to Current_Step you have not run the create table macro and are therefore not ready for this step"
    GoTo ExitSub
End If
Range("Current_Step").Value = 4
Application.ScreenUpdating = False
ActiveWorkbook.Save
    
' check and see if the naswer table already exists

Application.Goto Reference:="Ans_table"

TableExists = False
On Error GoTo Skip
If ActiveSheet.ListObjects("tAns").Name = "tAns" Then TableExists = True
Skip:
    On Error GoTo 0

 
 
 
 If TableExists = False Then
 
    Range("Ans_table").Select
    
    ActiveSheet.ListObjects.Add(xlSrcRange, Range("Ans_table"), , xlNo).Name _
        = "tAns"
        
        
    Range("tAns[#All]").Select
    ActiveSheet.ListObjects("tAns").ShowTableStyleRowStripes = False
    ActiveSheet.ListObjects("tAns").ShowAutoFilterDropDown = False
    ActiveSheet.ListObjects("tAns").TableStyle = "TableStyleLight20"
  End If
  
   
  'This next code came from  http://learnexcelmacro.com/wp/2017/09/save-excel-range-data-as-csv-file-through-excel-vba/
   
    Set tbl = Worksheets("Ans").ListObjects("tAns")
    csvFilePath = Replace(Replace(ThisWorkbook.Worksheets("Problem").Range("Word_Path").Value, "docx", "csv"), "0_", "0_Ans_")
    
    tblArr = tbl.DataBodyRange.Value

    fNum = FreeFile()
    Open csvFilePath For Output As #fNum
    For i = 1 To UBound(tblArr)
        rowArr = Application.Index(tblArr, i, 0)
        csvVal = VBA.Join(rowArr, ",")
        Print #1, csvVal
    Next
    Close #fNum
    Set tblArr = Nothing
    Set rowArr = Nothing
    Set csvVal = Nothing
ExitSub:
End Sub

Sub basecaseimage()

Dim DocLoc, BaseText, SubText As String
Dim Workbook_name As Variant
Dim i As Integer
Dim WordDoc, WordApp, oRng  As Object
Dim rBasecase, rSubs As Range

' using late binding


With Worksheets("Problem")

 If Worksheets("Problem").Range("Word_Path").Value = Empty Then
        MsgBox "No Word document in Word_Path"
        .Range("Word_Path").Select
        Exit Sub
  End If

DocLoc = Range("Word_Path").Value 'Word Document Filename and Path
        
        'Open Word Template
        On Error Resume Next 'If Word is already running
        Set WordApp = GetObject(, "Word.Application")
        If Err.Number <> 0 Then
        'Launch a new instance of Word
        Err.Clear
        'On Error GoTo Error_Handler
        Set WordApp = CreateObject("Word.Application")
        WordApp.Visible = True 'Make the application visible to the user
        End If
        
Set WordDoc = WordApp.Documents.Open(FileName:=DocLoc, ReadOnly:=False)  'Open Template
                 Set oRng = WordDoc.Range
                 With oRng.Find
                    .ClearFormatting
                    .Text = "b==" & "*" & "==b" '[ is a speical find character
                     .Forward = True
                     .MatchWildcards = True
                    .Replacement.ClearFormatting
                    .Wrap = wdFindContinue
                    If .Execute Then
                           
                           BaseText = oRng.Text
                           BaseText = Replace(BaseText, "b==", "")
                           BaseText = Replace(BaseText, "==b", "")
                           BaseText = Replace(BaseText, "p^^", "")
                           BaseText = Replace(BaseText, "^^p", ")")
                           
                           Worksheets("Problem").Range("B8").Value = BaseText
                           
                          Set rBasecase = Worksheets("Problem").Range("B8")
                           Set rSubs = Worksheets("Input").Range("B1")
                          
                                                   
                           For i = 0 To 30
                                 rBasecase.Replace What:="##" & rSubs.Offset(0, i).Value & "*" & "##", Replacement:=rSubs.Offset(1, i).Value
                           Next i
                          
                      
                       End If
                            BaseText = Worksheets("Problem").Range("B8").Value
                            
                       '    BaseText = "z== WTF ==z"
                        BaseText = Right(Trim(BaseText), 300)
                        BaseText = "z== " & BaseText & " ==z"
                        
                            
                 End With
                 Set oRng = Nothing
                 Set WordDoc = WordApp.Documents.Open(FileName:=DocLoc, ReadOnly:=False)  'Open Template
                 Set oRng = WordDoc.Range
                  With oRng.Find
                    .ClearFormatting
                    .Text = "z==*==z" '[ is a speical find character
                     .Forward = True
                     .MatchWildcards = True
                    .Replacement.ClearFormatting
                    .Replacement.Text = BaseText
                    .MatchCase = False
                
                    .Wrap = wdFindContinue
                    .Execute Forward:=True, Replace:=wdReplaceAll, Wrap:=wdFindContinue
                    
                    
                 ' not sure why the BaseText will not give the text to the replace function when I reset it to a text string it workd fine
                 
                 End With
                 
                    MsgBox (BaseText)
                 
      
            WordDoc.Close
          
      Set oRng = Nothing
      Set WordDoc = Nothing
     Set WordApp = Nothing
                 
End With
End Sub