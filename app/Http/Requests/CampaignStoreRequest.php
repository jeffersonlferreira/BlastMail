<?php

namespace App\Http\Requests;

use App\Models\Template;
use Illuminate\Foundation\Http\FormRequest;

class CampaignStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $tab = $this->route('tab');

        $defaults = [
            'name' => null,
            'subject' => null,
            'email_list_id' => null,
            'template_id' => null,
            'body' => null,
            'track_click' => null,
            'track_open' => null,
            'send_at' => null,
            'send_when' => null,
        ];

        $sessionData = array_merge($defaults, session('campaigns::create', []));

        $newInput = $this->all();

        foreach ($newInput as $key => $value) {
            if (($key === 'track_click' || $key === 'track_open') && isset($sessionData[$key])) {
                continue;
            }
            $sessionData[$key] = $value;
        }

        if (!empty($sessionData['template_id']) && blank($sessionData['body'])) {
            if ($template = Template::find($sessionData['template_id'])) {
                $sessionData['body'] = $template->body;
            }
        }

        $rules = [];

        if (blank($tab)) {
            $rules = [
                'name' => ['required', 'string', 'max:255'],
                'subject' => ['required', 'string', 'max:40'],
                'email_list_id' => ['required', 'exists:email_lists,id'],
                'template_id' => ['required', 'exists:templates,id'],
            ];
        }

        if ($tab === 'template') {
            $rules = ['body' => ['required', 'string']];
        }

        if ($tab === 'schedule') {
            $sendWhen = $sessionData['send_when'] ?? null;

            if ($sendWhen === 'now') {
                $sessionData['send_at'] = now();
            } elseif ($sendWhen === 'later') {
                $rules = ['send_at' => ['required', 'date', 'after:today']];
            } else {
                $rules = ['send_when' => ['required']];
            }
        }

        session()->put('campaigns::create', $sessionData);

        return $rules;
    }

    /**
     * Pega os dados finais da sessão para salvar no banco.
     * (Seu método original já estava bom, apenas um pequeno ajuste)
     */
    public function getData()
    {
        $session = session()->get('campaigns::create', []);

        unset(
            $session['_token'],
            $session['send_when']
        );

        $session['track_click'] = $session['track_click'] ?: false;
        $session['track_open'] = $session['track_open'] ?: false;

        return $session;
    }

    /**
     * Define para qual rota redirecionar após cada passo.
     * (Seu método original está perfeito)
     */
    public function getToRoute()
    {
        $tab = $this->route('tab');

        if (blank($tab)) {
            return route('campaigns.create', ['tab' => 'template']);
        }

        if ($tab == 'template') {
            return route('campaigns.create', ['tab' => 'schedule']);
        }

        return route('campaigns.index');
    }
}
