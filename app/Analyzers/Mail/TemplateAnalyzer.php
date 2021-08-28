<?php

namespace App\Analyzers\Mail;

use App\Models\Tamplate;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TemplateAnalyzer extends Tamplate
{
    protected $request = Null; // request

    /**
     * @var string
     */
    protected $storageDisk = 'public';

    /**
     * @return \Illuminate\Http\Response
     */
    public function store($request)
    {
        return $this->recordRequest($request)->with('success', __('Template წარმატებით დაემატა'));
    }

    /**
     * Delete template.
     *
     * @return \Illuminate\Http\Response
     */
    public function distroyRecord($id)
    {
        $template = $this->find($id);
        Storage::disk($this->storageDisk)->delete('template/'.$template->header_picture_path);
        Storage::disk($this->storageDisk)->delete('template/'.$template->footer_picture_path);
        $template->destroy($id);
        return redirect()->route('template.list')->with('success', __('Template წარმატებით წაიშალა'));
    }

    /**
     * Copy template.
     *
     * @return \Illuminate\Http\Response
     */
    public function copyRecord($id)
    {
        $record = $this->find($id);
        $copyRecord = $record->replicate();
        $copyRecord->header_picture_path = $record->header_picture_path ? $this->replicateLocalFile($record->header_picture_path) : Null;
        $copyRecord->footer_picture_path = $record->footer_picture_path ? $this->replicateLocalFile($record->footer_picture_path) : Null;
        $copyRecord->save();
        return redirect()->route('template.list')->with('success', __('Template წარმატებით დაკოპირდა'));
    }

    /**
     * Update template.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateRecord($request, $id)
    {
        $this->recordRequest($request, $id);
        return redirect()->route('template.list')->with('success', __('Template წარმატებით განახლდა'));
    }

    /**
     * Get random filename.
     *
     * @return string
     */
    public function replicateLocalFile($record)
    {
        $len = strval(time())[-1];
        $newFileName = Str::random($len).'copy'.$record;
        Storage::disk($this->storageDisk)->copy('template/'.$record, 'template/'.$newFileName);
        return $newFileName;
    }

    /**
     * Store database.
     *
     * @return \Illuminate\Http\Response
     */
    public function recordRequest($request, $id=NULL)
    {
        $column = $id ? $this->find($id): $this;

        $request->headerImage != NULL & $id != NULL ? $this->removePicture($column->header_picture_path) : '';
        $request->footerImage != NULL & $id != NULL ? $this->removePicture($column->footer_picture_path) : '';

        $column->name = $request->name;
        $column->footer_content = $request->footerText;

        $request->headerImage ? $this->uploatHeaderImage($request, $column) : '';
        $request->footerImage ? $this->uploatFooterImage($request, $column) : '';

        $column->save();
        return redirect()->route('template.list');
    }

    /**
     * Save header image into storage disk.
     */
    public function uploatHeaderImage($request, $column)
    {
        $file = $request->file('headerImage');
        $extension = $file->extension();
        $fileName = time().'.'.$extension;
        $request->headerImage->storeAs('template', $fileName, $this->storageDisk);
        $column->header_picture_path = strval($fileName);
    }

    /**
     * Save footer image into storage disk.
     */
    public function uploatFooterImage($request, $column)
    {
        $file = $request->file('footerImage');
        $extension = $file->extension();
        $fileName = time() + 1 .'.'.$extension;
        $request->footerImage->storeAs('template', $fileName,'public');
        $column->footer_picture_path = strval($fileName);
    }

    /**
     * Remove Picture.
     */
    public function removePicture($picture)
    {
        try
        {
            Storage::disk($this->storageDisk)->delete('template/'.$picture);
        }
        catch (Exception $e)
        {
            'Do nothing :)';
        }
    }
}