<?php

namespace App\Http\Controllers;

use App\Models\EmailList;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Email;
use Illuminate\Database\Eloquent\Builder;

class EmailListController extends Controller
{
    public function index()
    {
        $search = request('search', '');

        $emailLists = EmailList::query()
            ->withCount('subscribers')
            ->when(
                $search,
                fn(Builder $query) => $query
                    ->where('title', 'like', '%' . $search . '%')
                    ->orWhere('id', '=', $search)
            )
            ->paginate(5)->appends(compact('search')); //PAGINAÇÃO COM O SEARCHED

        return view('email-list.index', [
            'emailLists' => $emailLists,
            'search' => $search,
        ]);
    }

    public function create()
    {
        return view('email-list.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'max:255'],
            'file' => ['required', 'file', 'mimetypes:text/plain,text/csv'],
        ]);

        $emails = $this->getEmailsFromCsvFile($request->file('file'));

        DB::transaction(function () use ($request, $emails) {
            $emailList = EmailList::query()->create(['title' => $request->title]);
            $emailList->subscribers()->createMany($emails);
        });

        return to_route('email-list.index');
    }

    private function getEmailsFromCsvFile(UploadedFile $file): array
    {
        $fileHandle = fopen($file->getRealPath(), 'r');
        $items = [];

        while (($row = fgetcsv($fileHandle, null, ';')) !== false) {
            if ($row[0] == 'Name' && $row[1] == 'Email') {
                continue;
            }

            $items[] = [
                'name' => $row[0],
                'email' => $row[1],
            ];
        }

        fclose($fileHandle);

        return $items;
    }

    public function show(EmailList $emailList)
    {
        //
    }

    public function edit(EmailList $emailList)
    {
        //
    }

    public function update(Request $request, EmailList $emailList)
    {
        //
    }

    public function destroy(EmailList $emailList)
    {
        //
    }
}
