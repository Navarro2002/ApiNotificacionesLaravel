<?php

namespace App\Http\Controllers\Api;

use App\Models\Notificacion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CorreoEnviado;
use App\Models\User;
use App\Notifications\NotificacionCorreo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class NotificacionController extends Controller
{
    public function index()
    {
        return Notificacion::where('usuario_id', Auth::id())->latest()->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string',
            'mensaje' => 'required|string',
            'tipo' => 'in:info,warning,error,success',
            'usuario_id' => 'required|exists:users,id'
        ]);

        $notificacion = Notificacion::create([
            'titulo' => $request->titulo,
            'mensaje' => $request->mensaje,
            'tipo' => $request->tipo ?? 'info',
            'usuario_id' => $request->usuario_id
        ]);

        return response()->json($notificacion, 201);
    }

    public function marcarComoLeido($id)
    {
        $notificacion = Notificacion::where('id', $id)
            ->where('usuario_id', Auth::id())
            ->firstOrFail();

        $notificacion->leido = true;
        $notificacion->save();

        return response()->json(['message' => 'Notificación marcada como leída']);
    }

    public function enviarPorCorreo(Request $request)
    {
        $request->validate([
            'destinatario' => 'required|email',
            'asunto' => 'required|string|max:255',
            'mensaje' => 'required|string',
        ]);

        $rutaAdjunto = null;
        if ($request->hasFile('archivo_pdf')) {
            $rutaAdjunto = $request->file('archivo_pdf')->store('temp');
            $rutaAdjunto = storage_path('app/' . $rutaAdjunto);
        }

        Notification::route('mail', $request->destinatario)
            ->notify(new NotificacionCorreo(
                $request->asunto,
                $request->mensaje,
                $rutaAdjunto
            ));

        CorreoEnviado::create([
            'destinatario' => $request->destinatario,
            'asunto' => $request->asunto,
            'mensaje' => $request->mensaje,
            'enviado_en' => now(),
            'path' => $rutaAdjunto ?? '',
        ]);


        return response()->json(['mensaje' => 'Notificación enviada correctamente.']);
    }
}
