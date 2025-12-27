<?php
namespace App\Http\Controllers\Core;

use App\Charts\Dashboard;
use App\Charts\KotorVsBersih;
use App\Dao\Enums\TransactionType;
use App\Dao\Models\Core\User;
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

        $customer = Query::getCustomerByUser();
        if (count($customer) == 1) {
            $customer = $customer->first();
        }

        $customer_code = request('customer');

        $register = Register::select('register_qty')->sum('register_qty');
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
