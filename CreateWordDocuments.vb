Sub mergeDocuments()
Dim CustRow, CustCol, LastRow, TemplRow As Long
Dim DocLoc,  TemplName, FileName As String
Dim WordDoc, WordApp, OutApp, OutMail As Object
Dim WordContent As Word.Range
With Sheet1
  
  If .Range("B3").Value = Empty Then
    MsgBox "Please select a correct template from the drop down list"
    .Range("G3").Select
    Exit Sub
  End If
    TemplRow = .Range("B3").Value 'Set Template Row
    TemplName = .Range("G3").Value 'Set Template Name
    
   
    DocLoc = Sheet2.Range("F" & TemplRow).Value 'Word Document Filename
    
    'Open Word Template
    On Error Resume Next 'If Word is already running
    Set WordApp = GetObject("Word.Application")
    If Err.Number <> 0 Then
    'Launch a new instance of Word
    Err.Clear
    'On Error GoTo Error_Handler
    Set WordApp = CreateObject("Word.Application")
    WordApp.Visible = True 'Make the application visible to the user
    End If
    
    
    LastRow = .Range("E9999").End(xlUp).Row  'Determine Last Row in Table
        For CustRow = 8 To LastRow
                DaysSince = .Range("M" & CustRow).Value
                If TemplName <> .Range("N" & CustRow).Value And DaysSince >= FrDays And DaysSince <= ToDays Then
                                Set WordDoc = WordApp.Documents.Open(FileName:=DocLoc, ReadOnly:=False) 'Open Template
                                For CustCol = 5 To 13 'Move Through 9 Columns
                                    TagName = .Cells(7, CustCol).Value 'Tag Name
                                    TagValue = .Cells(CustRow, CustCol).Value 'Tag Value
                                     With WordDoc.Content.Find
                                        .Text = TagName
                                        .Replacement.Text = TagValue
                                        .Wrap = wdFindContinue
                                        .Execute Replace:=wdReplaceAll 'Find & Replace all instances
                                     End With
                                Next CustCol
                        
                        If .Range("I3").Value = "PDF" Then
                                       FileName = ThisWorkbook.Path & "\" & .Range("E" & CustRow).Value & "_" & .Range("F" & CustRow).Value & ".pdf" 'Create full filename & Path with current workbook location, Last Name & First Name
                                       WordDoc.ExportAsFixedFormat OutputFileName:=FileName, ExportFormat:=wdExportFormatPDF
                                       WordDoc.Close False
                                   Else: 'If Word
                                       FileName = ThisWorkbook.Path & "\" & .Range("E" & CustRow).Value & "_" & .Range("F" & CustRow).Value & ".docx"
                                       WordDoc.SaveAs FileName
                                   End If
                                   .Range("N" & CustRow).Value = TemplName 'Template Name
                                   .Range("O" & CustRow).Value = Now
                                    If .Range("P3").Value = "Email" Then
                                                  Set OutApp = CreateObject("Outlook.Application") 'Create Outlook Application
                                                  Set OutMail = OutApp.CreateItem(0) 'Create Email
                                                  With OutMail
                                                      .To = Sheet1.Range("K" & CustRow).Value
                                                      .Subject = "Hi, " & Sheet1.Range("F" & CustRow).Value & " We Miss You"
                                                      .Body = "Hello, " & Sheet1.Range("F" & CustRow).Value & " Its been a while since we have seen you so we wanted to send you a special letter. Please see the attached file"
                                                      .Attachments.Add FileName
                                                      .Display 'To send without Displaying change .Display to .Send
                                                  End With
                                    Else: 'Print Out
                                           WordDoc.PrintOut
                                           WordDoc.Close
                                    End If
                        Kill (FileName) 'Deletes the PDF or Word that was just created
            End If '3 condition met
        Next CustRow
        WordApp.Quit
End With
End Sub

