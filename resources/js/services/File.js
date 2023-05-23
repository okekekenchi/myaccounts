/**
 * Provides a wrapper around the xlsx library
 */


import XLSX from 'xlsx'
import saveAs from 'file-saver'

export default function()
{
  return {

    import: async function(file) {
      let content, workbook, worksheet, sheet_name, json_data = []

      content = await file.arrayBuffer()

      if (content) {
        workbook = XLSX.read(content)
        sheet_name = workbook.SheetNames[0]
        worksheet = workbook.Sheets[sheet_name]
        json_data = XLSX.utils.sheet_to_json(worksheet)
      }
      return json_data
    },

    export: async function(book_name, sheet_name, data_to_export, file_format) {
        
      let work_book = XLSX.utils.book_new(),
          work_sheet = XLSX.utils.json_to_sheet(data_to_export)

      work_book.Props = {
          Title: `${book_name} template`,
          Subject: book_name,
          Author: "Okeke Kenneth",
          CreatedDate: Date.now(),
          Company: "kenmaxi Technologies"
      }

      work_book.SheetNames.push(sheet_name)
      work_book.Sheets[sheet_name] = work_sheet

      let work_book_out = XLSX.write(work_book, {bookType: file_format,  type: 'binary'});

      function s2ab(s) {
          var buf = new ArrayBuffer(s.length)
          var view = new Uint8Array(buf)
          for (var i=0; i<s.length; i++) view[i] = s.charCodeAt(i) & 0xFF
          return buf
      }

      await saveAs( new Blob([ s2ab(work_book_out)],
                    {type:"application/octet-stream"}),
                    `${book_name}.${file_format}`
                  )
    }
  }
}