Option Explicit

Sub QRAddFiles()
Dim WordTempLoc, CSVTempLoc As FileDialog
Dim FirstRow As Long

Set WordTempLoc = Application.FileDialog(msoFileDialogFilePicker)
FirstRow = Worksheets("FileMerge").Range("G99999").End(xlUp).Row + 1 'First Available Row
With WordTempLoc
    .Title = "Select Problem Statement Word file"
    .Filters.Add "Word Type Files", "*.docx,*.doc", 1
    If .Show <> -1 Then GoTo NoSelection
    Worksheets("FileMerge").Range("G" & FirstRow).Value = Dir(.SelectedItems(1)) 'Document Name
    Worksheets("FileMerge").Range("H" & FirstRow).Value = .SelectedItems(1) 'Document Pathway
    .Filters.Clear
End With

' now get the corresponding input data file

Set CSVTempLoc = Application.FileDialog(msoFileDialogFilePicker)

With CSVTempLoc
    .Title = "Select Corresponding Input Data CSV file"
    .Filters.Add "CSV Type Files", "*.CSV", 1
    If .Show <> -1 Then GoTo NoSelection
    Worksheets("FileMerge").Range("I" & FirstRow).Value = Dir(.SelectedItems(1)) 'CSV Document Name
    Worksheets("FileMerge").Range("J" & FirstRow).Value = .SelectedItems(1) 'CSV Document Pathway
    .Filters.Clear
End With

Set WordTempLoc = Nothing
Set CSVTempLoc = Nothing

NoSelection:
End Sub
' the above is modified from the custom Letter creator by Randy Austin of ExcelFreelancersGroup


Sub mergeDocuments()
Dim iNumStu, iNumDocs, iDex, i, j, k, m, n, iNumVars As Integer
Dim rStu, rDocs As Range

Dim sStuName, sEmail, sSomString, sStuDoc, sDocName, sOutputDir, sVarVal, sPblmNum As String
Dim oWordDoc, oWordApp, oStuDoc As Object
Dim sDocLoc, sCsvLoc  As String
Dim wSheetCsv, wCsvSrc As Worksheet
Dim wBookCsv As Workbook
Dim lngJunk As Long
Dim oCsvRng, oDocRng, oDocHdr, oDocTxtBx As Object
Dim aSubVar(1 To 15) As String ' this is an array that contains the names of the variables from the csv input file
Dim rngStory As Word.Range

'Dim CustRow, CustCol, LastRow, TemplRow As Long
'Dim DocLoc, CSVLoc, TemplName, FileName As String
'Dim WordDoc, WordApp, OutApp, OutMail As Object
'Dim WordContent As Word.Range


'Open Word Template
    MsgBox "this may take a few minutes"
    
    Application.ScreenUpdating = False ' speed things up with
   
    On Error Resume Next 'If Word is already running
    Set oWordApp = GetObject("Word.Application")
    If Err.Number <> 0 Then
    'Launch a new instance of Word
    Err.Clear
    'On Error GoTo Error_Handler
    Set oWordApp = CreateObject("Word.Application")
     oWordApp.Visible = False 'Don't Make the application visible to the user to try to speed things up
     
    End If


  
    iNumStu = Worksheets("FileMerge").Range("B300").End(xlUp).Row - 8 'Last Row minus the first row that student names are in
    iNumDocs = Worksheets("FileMerge").Range("G300").End(xlUp).Row - 8    'Last Row minus the first row that document names are in
    
    
    'loop over the students with counter i and the documents with counter j
    Set rStu = Worksheets("FileMerge").Range("B9") ' the first student name normally the instructor since it is usually the bace case
    Set rDocs = Worksheets("FileMerge").Range("G9") ' the document name
    
    For i = 0 To iNumStu - 1
        sStuName = rStu.Offset(i, 0).Value
        sEmail = rStu.Offset(i, 1).Value
        iDex = rStu.Offset(i, 2).Value
        
        For j = 0 To iNumDocs - 1 ' loop thru all of the documents
        
            sDocLoc = rDocs.Offset(j, 1).Value
            sDocName = rDocs.Offset(j, 2).Value
            sCsvLoc = rDocs.Offset(j, 3).Value
            sOutputDir = rDocs.Offset(0, 4).Value ' make a directory in the same directory of the first problem
            sPblmNum = rDocs.Offset(j, 5).Value
   
            
            On Error Resume Next
                If i = 0 And j = 0 Then
                
                    MkDir (sOutputDir)
           
                End If
                
            
            
            Set oWordDoc = oWordApp.Documents.Open(FileName:=sDocLoc, ReadOnly:=True)
            
           sStuDoc = sOutputDir & "\i_" & iDex & "_" & sPblmNum & "_" & sStuName & "_" & j + 1 & ".docx"     ' name the individual files
 
           oWordDoc.SaveAs2 FileName:=sStuDoc
           Set oStuDoc = oWordApp.Documents.Open(FileName:=sStuDoc, ReadOnly:=False)
           Set oDocRng = oStuDoc.Range
            Set wBookCsv = Workbooks.Open(FileName:=sCsvLoc, ReadOnly:=True)
            Set wCsvSrc = wBookCsv.Worksheets("Sheet1")
            
                        
            ' read the variable names into an array and get the max number of variables
            k = 1
            Do While k < 15 ' max number of unique variables
                     With wCsvSrc
                         aSubVar(k) = Range("A1").Offset(0, k).Value
                          If (aSubVar(k) = "Null") Then
                             iNumVars = k - 1 ' the first time it hits "Null" then we hit the fisrt non varaibe column
                             k = 15
                           End If
                    
                     End With
                k = k + 1
            Loop
           'replacing the name there has got to be a better way but punted this searches the entire document I couldnt isolate the header as an object
           For Each oDocHdr In oStuDoc.StoryRanges
                With oDocHdr.Find
                    
                    .ClearFormatting
                    
                    .MatchWildcards = False
                    .Text = "##StuName##"
                      .Replacement.ClearFormatting
                    .Replacement.Text = sStuName
                    .Forward = True
                    .Wrap = wdFindContinue
                    .Format = False
                    .MatchSoundsLike = False
                    .MatchAllWordForms = False
                    .Execute Replace:=wdReplaceAll, Forward:=True, Wrap:=wdFindContinue
                    
                     .ClearFormatting
                     .Text = "##dex##"
                      .Replacement.ClearFormatting
                    .Replacement.Text = iDex
                    .Execute Replace:=wdReplaceAll, Forward:=True, Wrap:=wdFindContinue
                    
                     .ClearFormatting
                     .Text = "Grading Scheme: ##g_scheme##"
                      .Replacement.ClearFormatting
                    .Replacement.Text = ""
                    .Execute Replace:=wdReplaceAll, Forward:=True, Wrap:=wdFindContinue
                    
                   If j = 0 Then
                     .ClearFormatting
                     .Text = "v=="
                      .Replacement.ClearFormatting
                    .Replacement.Text = ""
                    .Execute Replace:=wdReplaceAll, Forward:=True, Wrap:=wdFindContinue
                    
                    .ClearFormatting
                     .Text = "==v"
                      .Replacement.ClearFormatting
                    .Replacement.Text = ""
                    .Execute Replace:=wdReplaceAll, Forward:=True, Wrap:=wdFindContinue
                    
                   Else
                    .MatchWildcards = True ' get rid of the directions if it is not the first problem in the homework set
                     .ClearFormatting
                     .Text = "v==*==v"
                      .Replacement.ClearFormatting
                    .Replacement.Text = ""
                    .Execute Replace:=wdReplaceAll, Forward:=True, Wrap:=wdFindContinue
                    
                   End If
                 
                End With
              
            Next oDocHdr



  
' some of these were lifted from an article by
' word MVP’s Doug Robbins and Greg Maxey
' with enhancements by Peter Hewett and Jonathan West I hope it will work with no word library

  'Fix the skipped blank Header/Footer problem as provided by Peter Hewett

  lngJunk = oStuDoc.Sections(1).Headers(1).Range.StoryType

  'Iterate through all story types in the current document

  For Each rngStory In oStuDoc.StoryRanges

    'Iterate through all linked stories

    Do

      With rngStory.Find

                For k = 1 To iNumVars
                        
                        With wCsvSrc
                            sVarVal = Range("A1").Offset(iDex, k).Value
                        End With
                   
                          .ClearFormatting
                          .Text = "##" & aSubVar(k) & "*" & "##"
                          .MatchWildcards = True
              
                           .Replacement.ClearFormatting
                         .Replacement.Text = sVarVal
                      
                         .Execute Replace:=wdReplaceAll, Forward:=True, Wrap:=wdFindContinue
                
                   Next k






   '     .Text = "##"
    '    .Replacement.Text = "I'm found"
   '     .Wrap = wdFindContinue
    '    .Execute Replace:=wdReplaceAll

      End With

      'Get next linked story (if any)

      Set rngStory = rngStory.NextStoryRange

    Loop Until rngStory Is Nothing

  Next






' For Each oDocTxtBx In oStuDoc.StoryRanges





' Next oDocTxtBx




            ' Next substitute the values this reads from the csv file but it would be faster if this was read into a 2 D array to start and never closed until the end
           
                 With oDocRng.Find
                
                     For k = 1 To iNumVars
                        
                        With wCsvSrc
                            sVarVal = Range("A1").Offset(iDex, k).Value
                        End With
                   
                          .ClearFormatting
                          .Text = "##" & aSubVar(k) & "*" & "##"
                          .MatchWildcards = True
              
                           .Replacement.ClearFormatting
                         .Replacement.Text = sVarVal
                      
                         .Execute Replace:=wdReplaceAll, Forward:=True, Wrap:=wdFindContinue
                
                     Next k
            ' then clean up the document with the document codes
                    
                     .ClearFormatting
                     .MatchWildcards = False
                     .MatchCase = False
                      .Text = "==p"
                      .Replacement.ClearFormatting
                    .Replacement.Text = ")"
                    .Execute Replace:=wdReplaceAll, Forward:=True, Wrap:=wdFindContinue
            
                    .ClearFormatting
                    .MatchCase = False
                     .MatchWildcards = True
                     .Text = "==[q-z]"
                      .Replacement.ClearFormatting
                    .Replacement.Text = ""
                    .Execute Replace:=wdReplaceAll, Forward:=True, Wrap:=wdFindContinue
            
                    .ClearFormatting
                     .MatchWildcards = True
                     .Text = "[p-z]=="
                      .Replacement.ClearFormatting
                    .Replacement.Text = ""
                    .Execute Replace:=wdReplaceAll, Forward:=True, Wrap:=wdFindContinue
            
            
            End With
         
            oStuDoc.Close
            wBookCsv.Close False
          
        Next j
 
    Next i
   
   ' need to add code that will combine files depending on what format the user wants
   
   
   
   
   
   
   MsgBox "Finished"
Application.ScreenUpdating = True
End Sub
    
    
   
'    DocLoc = Sheet2.Range("F" & TemplRow).Value 'Word Document Filename
'
'
'
'
'    LastRow = .Range("E9999").End(xlUp).Row  'Determine Last Row in Table
'        For CustRow = 8 To LastRow
'                DaysSince = .Range("M" & CustRow).Value
'                If TemplName <> .Range("N" & CustRow).Value And DaysSince >= FrDays And DaysSince <= ToDays Then
'                                Set WordDoc = WordApp.Documents.Open(FileName:=DocLoc, ReadOnly:=False) 'Open Template
'                                For CustCol = 5 To 13 'Move Through 9 Columns
'                                    TagName = .Cells(7, CustCol).Value 'Tag Name
'                                    TagValue = .Cells(CustRow, CustCol).Value 'Tag Value
'                                     With WordDoc.Content.Find
'                                        .Text = TagName
'                                        .Replacement.Text = TagValue
'                                        .wrap = wdFindContinue
'                                        .Execute Replace:=wdReplaceAll 'Find & Replace all instances
'                                     End With
'                                Next CustCol
'
'                        If .Range("I3").Value = "PDF" Then
'                                       FileName = ThisWorkbook.Path & "\" & .Range("E" & CustRow).Value & "_" & .Range("F" & CustRow).Value & ".pdf" 'Create full filename & Path with current workbook location, Last Name & First Name
'                                       WordDoc.ExportAsFixedFormat OutputFileName:=FileName, ExportFormat:=wdExportFormatPDF
'                                       WordDoc.Close False
'                                   Else: 'If Word
'                                       FileName = ThisWorkbook.Path & "\" & .Range("E" & CustRow).Value & "_" & .Range("F" & CustRow).Value & ".docx"
'                                       WordDoc.SaveAs FileName
'                                   End If
'                                   .Range("N" & CustRow).Value = TemplName 'Template Name
'                                   .Range("O" & CustRow).Value = Now
'                                    If .Range("P3").Value = "Email" Then
'                                                  Set OutApp = CreateObject("Outlook.Application") 'Create Outlook Application
'                                                  Set OutMail = OutApp.CreateItem(0) 'Create Email
'                                                  With OutMail
'                                                      .To = Sheet1.Range("K" & CustRow).Value
'                                                      .Subject = "Hi, " & Sheet1.Range("F" & CustRow).Value & " We Miss You"
'                                                      .Body = "Hello, " & Sheet1.Range("F" & CustRow).Value & " Its been a while since we have seen you so we wanted to send you a special letter. Please see the attached file"
'                                                      .Attachments.Add FileName
'                                                      .Display 'To send without Displaying change .Display to .Send
'                                                  End With
'                                    Else: 'Print Out
'                                           WordDoc.PrintOut
'                                           WordDoc.Close
'                                    End If
'                        Kill (FileName) 'Deletes the PDF or Word that was just created
'            End If '3 condition met
'        Next CustRow
'        WordApp.Quit
'End With
'End Sub

' the above is modified from the custom Letter creator by Randy Austin of ExcelFreelancersGroup
