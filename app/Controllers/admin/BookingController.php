<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BookingsModel;
use Dompdf\Dompdf;
use Dompdf\Options;

class BookingController extends BaseController
{

    public function index()
    {
        // Check if the user is logged in
        if (!session()->has('user_id')) {
            return redirect()->to('/admin/login');
        }

        $bModel = new BookingsModel();
        $bresult = $bModel->select('bookings.*, properties.propertyname')
        ->join('properties', 'properties.property_id = bookings.property_id')
        ->findAll();;

        $data = [
            'title' => 'Booking | Green Mountain Homes',
            'description' => 'Experience the epitome of mountain living at our newly remodeled 5-bedroom ski home nestled in the heart of the Green Mountains, just minutes from both Bromley and Stratton. A winter wonderland awaits with ski-in, ski-out access and daily shuttle service to the slopes, ensuring effortless enjoyment for your skiing adventures. This spacious retreat comfortably accommodates up to 12 guests, featuring two master bedrooms and three additional bedrooms with queen and double bunk beds. Whether you’re carving the slopes, snowmobiling through pristine landscapes, or exploring Manchester’s fine dining and shopping, our mountain haven provides the perfect base for all your Southern Vermont adventures. Enjoy the cozy comforts of our newly renovated property set atop the snow-laced mountains of Vermont',
            'session' => \Config\Services::session(),
            'records' => $bresult,
            'activelink' => 'booking',
        ];
        return view('pages/admin/booking', $data);
    }
    public function delete($id)
    {
        // Delete the data from the database
        $bModel = new BookingsModel();
        $item = $bModel->find($id);

        if ($item) {
            $bModel->delete($id);
        }
        // Return a JSON response indicating success
        return $this->response->setJSON(['status' => 'success']);
    }
    public function approve($id)
    {
        $data = [
            'status' => 'Approved'
        ];
        $bModel = new BookingsModel();
        $bookingId = $bModel->update($id, $data);
        $response = [
            'status' => 'success',
            'message' => 'Booking has been approved!',
        ];

        return $this->response->setJSON($response);
    }
    public function exportToCsv()
    {
        // Load the model
        $bookingModel = new BookingsModel(); // Replace YourModel with the actual model name

        // Fetch data from the database
        $data = $bookingModel->findAll();

        if (empty($data)) {
            return redirect()->to(base_url()); // Redirect to home or handle the case where no data is available
        }

        // Extract column headers from the first row
        $headers = array_keys($data[0]);

        // Add headers as the first row in the data array
        array_unshift($data, $headers);

        // Generate CSV content
        $csvContent = $this->arrayToCsv($data);

        // Set the CSV file headers
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename="booking_export.csv"');

        // Output the CSV content
        echo $csvContent;
        exit();
    }

    // Helper function to convert array to CSV
    private function arrayToCsv(array &$data, $delimiter = ',', $enclosure = '"', $escape_char = "\\")
    {
        $output = fopen('php://temp', 'w');

        foreach ($data as $row) {
            fputcsv($output, $row, $delimiter, $enclosure, $escape_char);
        }

        fseek($output, 0);

        $csvContent = stream_get_contents($output);

        fclose($output);

        return $csvContent;
    }
    public function generatePdf($id)
    {

        $bModel = new BookingsModel();
        $bresult = $bModel->where('booking_id', $id)->findAll();
        $data['bresult'] = $bresult;
        // Load the Dompdf library
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $dompdf = new Dompdf($options);

        // HTML content for the PDF
        $html = view('components/bookingPDF', $data);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // Set paper size (optional)
        $dompdf->setPaper('letter', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF
        $dompdf->stream('document.pdf', ['Attachment' => false]);
    }
}
