<?php
namespace App\Http\Controllers;

use App\Models\Apk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApkController extends Controller
{
    /**
     * @OA\Post(
     *     path="/upload-apk",
     *     summary="Upload APK file",
     *     tags={"APK"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="version",
     *                     type="string",
     *                     description="Version of the APK"
     *                 ),
     *                 @OA\Property(
     *                     property="apk_file",
     *                     type="file",
     *                     description="APK file to upload"
     *                 ),
     *                 required={"version", "apk_file"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tải lên APK thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Tải lên APK thành công!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Tải lên APK thất bại",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Tải lên APK thất bại.")
     *         )
     *     )
     * )
     */
    public function upload(Request $request)
    {
        // Xác thực dữ liệu
        $request->validate([
            'version' => 'required|string|max:255',
            'apk_file' => 'required|file|mimes:apk|max:102400', // Giới hạn 100MB
        ]);

        // Lưu tệp APK
        if ($request->hasFile('apk_file')) {
            $file = $request->file('apk_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('apks', $fileName, 'public'); // Lưu vào storage/app/public/apks

            // Lưu thông tin vào database
            Apk::create([
                'version' => $request->version,
                'file_name' => $fileName,
                'file_path' => $filePath,
            ]);

            return redirect()->back()->with('success', 'Tải lên APK thành công!');
        }

        return redirect()->back()->with('error', 'Tải lên APK thất bại.');
    }
}
