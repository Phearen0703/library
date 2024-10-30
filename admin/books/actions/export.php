<?php
include($_SERVER['DOCUMENT_ROOT']."/library/config.php");
include($_SERVER['DOCUMENT_ROOT'] . "/library/vendor/autoload.php");

// Fetch parameters from the URL
$from = isset($_GET['from']) ? $_GET['from'] : '';
$to = isset($_GET['to']) ? $_GET['to'] : '';
$keyOrder = isset($_GET['keyOrder']) ? $_GET['keyOrder'] : '';
$orderBy = isset($_GET['orderBy']) ? $_GET['orderBy'] : '';
$searchCondition = isset($_GET['searchCondition']) ? $_GET['searchCondition'] : ''; // Initialize search condition

// Fetch data from the database
$books = $conn->query("SELECT tblbook.*,   
                       tblroles.RoleID as user_id, 
                       tbluser.FirstName, 
                       tbluser.LastName,
                       tbllanguage.LangName as lang_name,
                       tblcategory.CatName as main_type,
                       tblsubcategory.SubCatName as sub_type
                       FROM tblbook
                       LEFT JOIN tblroles ON tblbook.RoleID = tblroles.RoleID
                       LEFT JOIN tbluser ON tblroles.UserID = tbluser.UserID
                       LEFT JOIN tbllanguage ON tblbook.LangID = tbllanguage.LangID
                       LEFT JOIN tblsubcategory ON tblbook.SubCatID = tblsubcategory.SubCatID
                       LEFT JOIN tblcategory ON tblsubcategory.CatID = tblcategory.CatID
                       WHERE BRegistered >= '$from' AND BRegistered <= '$to'
                       $searchCondition
                       ORDER BY $keyOrder $orderBy");

// Load PhpSpreadsheet classes
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;



// Create new Spreadsheet object
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();



// Merge cells for the header section
$sheet->mergeCells('A1:M1');
$sheet->mergeCells('A2:M2');
$sheet->mergeCells('A6:M6');


// Set header text
$sheet->setCellValue('A1', 'ព្រះរាជាណាចក្រកម្ពុជា');
$sheet->setCellValue('A2', 'ជាតិ សាសនា ព្រះមហាក្សត្រ');
$sheet->setCellValue('B4', 'ក្រសួងសេដ្ឋកិច្ចនិងហិរញ្ញវត្ថុ');
$sheet->setCellValue('B5', 'នាយកដ្ឋានបណ្ណសារ');
$sheet->setCellValue('A6', 'របាយការណ៍សៀវភៅនៅក្នុងបណ្ណណាល័យ');



// Set font styles for the header
$sheet->getStyle('A1:A6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);


// Insert the logo
$logoPath = "../../Assets/image/logo.png";

if (!file_exists($logoPath)) {
    die("Image not found: " . $logoPath);
    
}

$logo = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
$logo->setName('Logo');
$logo->setDescription('Logo');
$logo->setPath($logoPath); // Ensure the path is correct
$logo->setCoordinates('B1'); // Set starting position
$logo->setHeight(100); // Adjust height of the logo
$logo->setWorksheet($spreadsheet->getActiveSheet());





// Set the document properties (optional)
$spreadsheet->getProperties()->setCreator("Library System")
    ->setLastModifiedBy("Library System")
    ->setTitle("Books Export")
    ->setSubject("Books Export")
    ->setDescription("Export of books data including Khmer Unicode");




// Set default font for Khmer Unicode support
$spreadsheet->getDefaultStyle()->getFont()->setName('Khmer OS Battambang');

// Set column headings
for ($row = 1; $row <= 7; $row++) {

    // Style headers
    $sheet->getStyle('A' . $row . ':M' . $row)->getFont()->setSize(10);
    $sheet ->getStyle('A' . $row . ':M' . $row)->getFont()->setName("Khmer OS Moul Light");
    $sheet->getStyle('A' . $row . ':M' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    
}

$row =7;
// Set the column headings starting at row 5
$sheet->setCellValue('A' . $row, 'លេខរៀង');
$sheet->setCellValue('B' . $row, 'ចំណងជើង');
$sheet->setCellValue('C' . $row, 'អ្នកនិពន្ធ');
$sheet->setCellValue('D' . $row, 'ប្រភព');
$sheet->setCellValue('E' . $row, 'ភាសា');
$sheet->setCellValue('F' . $row, 'ប្រភេទ');
$sheet->setCellValue('G' . $row, 'ឆ្នាំបោះពុម្ព');
$sheet->setCellValue('H' . $row, 'ទំព័រ');
$sheet->setCellValue('I' . $row, 'ចំនួន');
$sheet->setCellValue('J' . $row, 'តម្លៃ');
$sheet->setCellValue('K' . $row, 'កាលបរិច្ឆេទបញ្ចូល');
$sheet->setCellValue('L' . $row, 'កូដសៀវភៅ');
$sheet->setCellValue('M' . $row, 'អ្នកប្រើប្រាស់');

 // Style each data row (borders)
 $sheet->getStyle('A' . $row . ':M' . $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);



$sheet->getStyle('A:M' )->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A:M')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER); 

$sheet->getStyle('B1:B1048576')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
$sheet->getStyle('B1:B1048576')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);





// Set the width for column B
$sheet->getColumnDimension('B')->setWidth(35); // Set the width of Column B to 10
$sheet->getColumnDimension('K')->setWidth(18); 
$sheet->getColumnDimension('L')->setWidth(12); 
$sheet->getColumnDimension('M')->setWidth(13); 
$sheet->getColumnDimension('F')->setWidth(15); 
$sheet->getColumnDimension('C')->setWidth(15); 


// Wrap text for the entire range of Column B (from row 5 to the last populated row)
$sheet->getStyle('B')->getAlignment()->setWrapText(true); // Adjust the end row as necessary
$sheet->getStyle('F')->getAlignment()->setWrapText(true); 
$sheet->getStyle('C')->getAlignment()->setWrapText(true); 


// Populate spreadsheet with data
$row = 8;
$num = 0;
while ($book = $books->fetch_object()) {
    $sheet->setCellValue('A' . $row, ++$num);
    $sheet->setCellValue('B' . $row, $book->BTitle); // Column B text will now wrap
    $sheet->setCellValue('C' . $row, $book->BAuthor);
    $sheet->setCellValue('D' . $row, $book->BSource);
    $sheet->setCellValue('E' . $row, $book->lang_name);
    $sheet->setCellValue('F' . $row, $book->main_type . " - " . $book->sub_type);
    $sheet->setCellValue('G' . $row, $book->BPublished);
    $sheet->setCellValue('H' . $row, $book->BPage);
    $sheet->setCellValue('I' . $row, $book->BStock);
    $sheet->setCellValue('J' . $row, $book->BPrice);
    $sheet->setCellValue('K' . $row, $book->BRegistered);
    $sheet->setCellValue('L' . $row, $book->FullCode);
    $sheet->setCellValue('M' . $row, $book->LastName . " " . $book->FirstName);

     // Style each data row (borders)
 $sheet->getStyle('A' . $row . ':M' . $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
    $row++;
}
$report_at = date('Y-m-d');

// Set response headers for file download
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $report_at . '_របាយការណ៍សៀវភៅ.xlsx"');
header('Cache-Control: max-age=0');

// Write the Excel file
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
