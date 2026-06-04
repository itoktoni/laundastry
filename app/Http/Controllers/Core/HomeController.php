<?php
namespace App\Http\Controllers\Core;

use App\Charts\Dashboard;
use App\Charts\KotorVsBersih;
use App\Dao\Enums\TransactionType;
use App\Dao\Models\Core\User;
use App\Dao\Models\Customer;
use App\Dao\Models\Jenis;
use App\Dao\Models\Lokasi;
use App\Dao\Models\Opname;
use App\Dao\Models\Pending;
use App\Dao\Models\Register;
use App\Dao\Models\Transaksi;
use App\Dao\Traits\RedirectAuth;
use App\Http\Controllers\Controller;
use Plugins\Query;

class HomeController extends Controller
{
    use RedirectAuth;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        if (auth()->check()) {
            return redirect()->route('login');
        }
    }

    public function cms()
    {
        $secret = env('APP_KEY');

        $payload = [
            'email' => auth()->user()->email,
            'time'  => time(),
        ];

        $b64 = base64_encode(json_encode($payload));

        $sig = hash_hmac('sha256', $b64, $secret);

        $token = $b64 . '.' . $sig;

        return redirect(env('WP_URL') . "/wordpress-auto-login?token={$token}");
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(KotorVsBersih $chart)
    {
        if (empty(auth()->user())) {
            header('Location: ' . route('public'));
        }

        $template = auth()->user()->role;
        $customer_code = request('customer');

        $customer = Query::getCustomerByUser();
        if (count($customer) == 1) {
            $customer_code = array_keys($customer->toArray())[0];

            $customer = $customer->first();
        }


        $register = Register::select('register_qty')
                ->when($customer_code, function ($query) use ($customer_code) {
                    return $query->where('register_code_customer', $customer_code);
                })->sum('register_qty');

        $tanggal  = date('Y-m-d');

        $bersih = Transaksi::select(Transaksi::field_bersih())
            ->where(Transaksi::field_report(), $tanggal)
            ->when($customer_code, function ($query) use ($customer_code) {
                return $query->where(Transaksi::field_customer_code(), $customer_code);
            })
            ->sum(Transaksi::field_bersih());

        $kotor = Transaksi::select(Transaksi::field_scan())
            ->where(Transaksi::field_tanggal(), $tanggal)
            ->where(Transaksi::field_status(), TransactionType::KOTOR)
            ->when($customer_code, function ($query) use ($customer_code) {
                return $query->where(Transaksi::field_customer_code(), $customer_code);
            })
            ->sum(Transaksi::field_scan());

        $reject = Transaksi::select(Transaksi::field_scan())
            ->where(Transaksi::field_tanggal(), $tanggal)
            ->where(Transaksi::field_status(), TransactionType::REJECT)
            ->when($customer_code, function ($query) use ($customer_code) {
                return $query->where(Transaksi::field_customer_code(), $customer_code);
            })
            ->sum(Transaksi::field_scan());

        $rewash = Transaksi::select(Transaksi::field_scan())
            ->where(Transaksi::field_tanggal(), $tanggal)
            ->where(Transaksi::field_status(), TransactionType::REWASH)
            ->when($customer_code, function ($query) use ($customer_code) {
                return $query->where(Transaksi::field_customer_code(), $customer_code);
            })
            ->sum(Transaksi::field_scan());

        $pending_kotor = Pending::select('sisa')
            ->where(Transaksi::field_status(), TransactionType::KOTOR)
            ->where(Transaksi::field_pending(), '>=', 1)
            ->when($customer_code, function ($query) use ($customer_code) {
                return $query->where(Transaksi::field_customer_code(), $customer_code);
            })
            ->sum('sisa');

        $pending_reject = Pending::select('sisa')
            ->where(Transaksi::field_status(), TransactionType::REJECT)
            ->where(Transaksi::field_pending(), '>=', 1)
            ->when($customer_code, function ($query) use ($customer_code) {
                return $query->where(Transaksi::field_customer_code(), $customer_code);
            })
            ->sum('sisa');

        $pending_rewash = Pending::select('sisa')
            ->where(Transaksi::field_status(), TransactionType::REWASH)
            ->where(Transaksi::field_pending(), '>=', 1)
            ->when($customer_code, function ($query) use ($customer_code) {
                return $query->where(Transaksi::field_customer_code(), $customer_code);
            })
            ->sum('sisa');

        $opname = Opname::select(Opname::field_primary())->count();

        $available = $register - ($kotor + $reject + $rewash + $pending_kotor + $pending_reject + $pending_rewash);

        return view('core.home.' . $template, [
            'model'          => new Transaksi(),
            'type'          => TransactionType::getOptions([TransactionType::KOTOR, TransactionType::REJECT, TransactionType::REWASH]),
            'chart'          => $chart->build(),
            'register'       => $register,
            'bersih'         => $bersih,
            'kotor'          => $kotor,
            'customer'       => $customer,
            'reject'         => $reject,
            'rewash'         => $rewash,
            'pending_kotor'  => $pending_kotor,
            'pending_reject' => $pending_reject,
            'pending_rewash' => $pending_rewash,
            'available'      => $available,
            'opname'         => $opname,
        ]);
    }

    public function upload()
    {
        // Get uploaded file from request
        $file = request()->file('upload');

        if (!$file || !$file->isValid()) {
            return redirect()->back()->with('error', 'File tidak valid atau tidak ditemukan');
        }

        // Get the temp path of uploaded file
        $filePath = $file->getRealPath();

        // Use OpenSpout to read xlsx file
        $reader = new \OpenSpout\Reader\XLSX\Reader();
        $reader->open($filePath);

        $customerName = null;
        $customer_code = request('customer', null);
        $tanggalTransaksi = request('tanggal', null);
        $type = request('type', null);

        $rowData = [];

        foreach ($reader->getSheetIterator() as $sheet) {
            $rowNum = 0;

            foreach ($sheet->getRowIterator() as $row) {
                // Get raw cells as array
                $cells = [];
                foreach ($row->getCells() as $cell) {
                    $cells[] = $cell->getValue();
                }

                $rowNum++;

                // Row 2: Customer name (RS QODR2)
                if ($rowNum === 2 && !$customer_code) {
                    $customerName = isset($cells[0]) ? trim((string) $cells[0]) : '';
                    $customer_code = Customer::where('customer_nama', 'like', '%' . $customerName . '%')->value('customer_code');
                    continue;
                }

                // Row 5: Tanggal transaksi (PERIODE 23/05/2026)
                if ($rowNum === 5 && !$tanggalTransaksi) {
                    $tanggalRaw = isset($cells[4]) ? trim((string) $cells[4]) : '';
                    // Extract date from format "PERIODE 23/05/2026"
                    if (preg_match('/(\d{2}\/\d{2}\/\d{4})/', $tanggalRaw, $matches)) {
                        $tanggalTransaksi = $matches[1];
                    }

                    continue;
                }

                // Skip row 1, 4-8 (title and headers)
                if ($rowNum === 1 || ($rowNum >= 5 && $rowNum <= 6)) {
                    continue;
                }

                // Row 9 onwards: Data rows - only include if NO exists
                if ($rowNum >= 6) {
                    // Get NO value (first column)
                    $noValue = isset($cells[0]) ? $cells[0] : null;
                    // Only include rows that have a valid NO (numeric value)
                    if ($noValue !== null && $noValue > 0) {
                        $namaLinen = isset($cells[1]) ? trim((string) $cells[1]) : '';

                        $rowData[] = [
                            'no' => $noValue,
                            'nama_linen' => $namaLinen,
                            'lokasi' => isset($cells[2]) ? trim((string) $cells[2]) : '',
                            'berat' => isset($cells[3]) ? $this->parseDecimal($cells[3]) : 0,
                            'kotor_non_infeksius' => isset($cells[4]) ? $this->parseNumeric($cells[4]) : 0,
                            'kotor_infeksius' => isset($cells[5]) ? $this->parseNumeric($cells[5]) : 0,
                            'qc_non_infeksius' => isset($cells[6]) ? $this->parseNumeric($cells[6]) : 0,
                            'qc_infeksius' => isset($cells[7]) ? $this->parseNumeric($cells[7]) : 0,
                            'selisih_qc_non_infeksius' => isset($cells[8]) ? $this->parseNumeric($cells[8]) : 0,
                            'selisih_qc_infeksius' => isset($cells[9]) ? $this->parseNumeric($cells[9]) : 0,
                            'total_kotor_non_infeksius' => isset($cells[10]) ? $this->parseNumeric($cells[10]) : 0,
                            'total_kotor_infeksius' => isset($cells[11]) ? $this->parseNumeric($cells[11]) : 0,
                            'bersih_non_infeksius' => isset($cells[12]) ? $this->parseNumeric($cells[12]) : 0,
                            'bersih_infeksius' => isset($cells[13]) ? $this->parseNumeric($cells[13]) : 0,
                            'kurang_kirim' => isset($cells[14]) ? $this->parseNumeric($cells[14]) : 0,
                            'phisik_diterima' => isset($cells[15]) ? $this->parseNumeric($cells[15]) : 0,
                        ];
                    }
                }
            }
        }

        $reader->close();

        // Get all unique nama_linen values and search in Jenis table
        $namaLinenList = array_unique(array_column($rowData, 'nama_linen'));
        $jenisList = Jenis::whereIn('jenis_nama', $namaLinenList)->get()->keyBy('jenis_nama');

        // Get all unique lokasi values and search in Lokasi table
        $lokasiList = array_unique(array_filter(array_column($rowData, 'lokasi')));
        $lokasiList = Lokasi::whereIn('lokasi_nama', $lokasiList)->get()->keyBy('lokasi_nama');

        $insert = [];
        $code_scan = 'UPL-'.$customer_code.'-'.date('Ymd').'-'.unic(5);
        $code_packing = 'PACK-'.$customer_code.'-'.date('Ymd').'-'.unic(5);
        $code_bersih = 'BSH-'.$customer_code.'-'.date('Ymd').'-'.unic(5);
        $now = date('Y-m-d H:i:s');

                // Convert tanggalTransaksi from d/m/Y to Y-m-d
        $tanggalFormatted = $this->parseDate($tanggalTransaksi);
        $user = auth()->user()->id;
        // Add id_linen and id_lokasi to each rowData
        foreach ($rowData as $row) {
            if (isset($jenisList[$row['nama_linen']])) {

                $scan = $row['kotor_non_infeksius'] + $row['kotor_infeksius'];
                $qc = $row['qc_non_infeksius'] + $row['qc_infeksius'];
                $bersih = $row['bersih_non_infeksius'] + $row['bersih_infeksius'];
                $pending = $qc - $bersih;
                $code_pending = null;
                $pending_at = null;
                $pending_by = null;

                if($pending > 0)
                {
                    $code_pending = 'PND-'.$customer_code.'-'.date('Ymd').'-'.unic(5);
                    $pending_at = $tanggalFormatted;
                    $pending_by = $user;
                }

                $insert[] = [
                    'transaksi_code_scan' => $code_scan,
                    'transaksi_code_packing' => $code_packing,
                    'transaksi_code_bersih' => $code_bersih,
                    'transaksi_code_customer' => $customer_code,
                    'transaksi_id_jenis' => $jenisList[$row['nama_linen']]->jenis_id,
                    'transaksi_id_lokasi' => isset($lokasiList[$row['lokasi']]) ? $lokasiList[$row['lokasi']]->lokasi_id : null,
                    'transaksi_tanggal' => $tanggalFormatted,
                    'transaksi_report' => $tanggalFormatted,
                    'transaksi_qc_at' => $tanggalFormatted,
                    'transaksi_qc_by' => $user,
                    'transaksi_bersih_at' => $tanggalFormatted,
                    'transaksi_bersih_by' => $user,
                    'transaksi_created_at' => $now,
                    'transaksi_created_by' => $user,
                    'transaksi_updated_at' => $now,
                    'transaksi_updated_by' => $user,
                    'transaksi_code_category' => 'NORMAL',
                    'transaksi_status' => $type ?? TransactionType::KOTOR,
                    'transaksi_scan' => $scan,
                    'transaksi_qc' => $qc,
                    'transaksi_bersih' => $bersih,
                    'transaksi_pending' => $pending,
                    'transaksi_code_pending' => $code_pending,
                    'transaksi_pending_at' => $pending_at,
                    'transaksi_pending_by' => $pending_by,
                ];
            }
        }

        if(!empty($insert))
        {
            Transaksi::insert($insert);
        }
        else
        {
            return redirect()->back()->with('error', 'Tidak ada data yang valid untuk diinsert. Pastikan nama linen sesuai dengan data master jenis.');
        }

        return redirect()->back()->with('success', 'Total data diupload : ' . count($insert));
    }

    /**
     * Parse numeric value from cell
     */
    private function parseNumeric($value)
    {
        if (empty($value) || $value === '=') {
            return 0;
        }

        // Remove any formatting/characters
        $value = trim($value);

        // If it's a formula or starts with =, return 0
        if (str_starts_with($value, '=')) {
            return 0;
        }

        // Try to extract numeric value
        if (is_numeric($value)) {
            return (int) $value;
        }

        // Remove thousand separators and parse
        $cleaned = preg_replace('/[^\d,.-]/', '', $value);
        $cleaned = str_replace(',', '.', $cleaned);

        return is_numeric($cleaned) ? (int) floatval($cleaned) : 0;
    }

    /**
     * Parse decimal value from cell (for berat)
     */
    private function parseDecimal($value)
    {
        if (empty($value) || $value === '=') {
            return 0;
        }

        $value = trim($value);

        // If it's a formula or starts with =, return 0
        if (str_starts_with($value, '=')) {
            return 0;
        }

        // Handle numeric values directly
        if (is_numeric($value)) {
            return (float) $value;
        }

        // Handle European format (1.234,56) or other formats
        // Remove all non-numeric except comma and dot
        $cleaned = preg_replace('/[^\d,.]/', '', $value);

        // Check if comma is decimal separator (e.g., 12,5 = 12.5)
        if (strpos($cleaned, ',') !== false && strpos($cleaned, '.') !== false) {
            // Both exist, assume last one is decimal separator
            $lastComma = strrpos($cleaned, ',');
            $lastDot = strrpos($cleaned, '.');
            if ($lastComma > $lastDot) {
                // Comma is decimal separator (European format)
                $cleaned = str_replace('.', '', $cleaned);
            } else {
                // Dot is decimal separator
                $cleaned = str_replace(',', '', $cleaned);
            }
        } elseif (strpos($cleaned, ',') !== false) {
            // Only comma exists
            if (substr_count($cleaned, ',') == 1) {
                // Single comma - could be decimal separator
                $parts = explode(',', $cleaned);
                if (strlen($parts[1]) <= 2) {
                    // Likely decimal separator
                    $cleaned = str_replace(',', '.', $cleaned);
                } else {
                    // Likely thousand separator
                    $cleaned = str_replace(',', '', $cleaned);
                }
            } else {
                // Multiple commas - likely thousand separators
                $cleaned = str_replace(',', '', $cleaned);
            }
        }

        return is_numeric($cleaned) ? (float) $cleaned : 0;
    }

    /**
     * Parse date from d/m/Y to Y-m-d
     */
    private function parseDate($value)
    {
        if (empty($value)) {
            return null;
        }

        $value = trim($value);

        // If already in Y-m-d format, return as is
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            return $value;
        }

        // Parse d/m/Y format
        if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $value, $matches)) {
            $day = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
            $month = str_pad($matches[2], 2, '0', STR_PAD_LEFT);
            $year = $matches[3];
            return $year . '-' . $month . '-' . $day;
        }

        return null;
    }

    public function delete($code)
    {
        $navigation = session()->get('navigation');
        if (! empty($navigation) && array_key_exists($code, $navigation)) {
            unset($navigation[$code]);
            session()->put('navigation', $navigation);
        }

        return redirect()->back();
    }

    public function console()
    {
        return LaravelWebConsole::show();
    }

    public function doc()
    {
        return view('doc');
    }

    public function error402()
    {
        return view('errors.402');
    }
}
