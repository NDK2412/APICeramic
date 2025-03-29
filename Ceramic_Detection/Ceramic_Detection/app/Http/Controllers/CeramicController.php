namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CeramicController extends Controller
{
    public function index()
    {
        // Gọi API từ FastAPI để lấy danh sách ảnh
        $response = Http::get('http://localhost:60074/images');
        $images = $response->successful() ? $response->json()['images'] : [];

        return view('ceramic.index', compact('images'));
    }
}