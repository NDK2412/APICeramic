namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PredictController extends Controller
{
    public function predict(Request $request)
    {
        $file = $request->file('file');

        $response = Http::attach(
            'file', file_get_contents($file), $file->getClientOriginalName()
        )->post('http://127.0.0.1:60074/predict');

        return view('result', ['result' => $response->json()]);
    }
}
