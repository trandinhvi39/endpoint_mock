<?php

namespace App\Traits\Repositories;

use Carbon\Carbon;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Log;
use Illuminate\Support\Facades\Storage;

trait UploadableTrait
{
    public function uploadableTimestamp()
    {
        return 'Y/m';
    }

    public function uploadFile(UploadedFile $file, $path = 'other', $uploadDisk = 'image')
    {
        $storage = Storage::disk($uploadDisk);
        $target = $this->getTarget($path);
        $fileName = $this->getFileName($file);
        $destinationTarget = $target . '/' . $fileName;
        $count = 0;

        while ($storage->has($destinationTarget)) {
            $count++;
            $destinationTarget = $target . '/' . $count . '-' . $fileName;
        }

        $saved = $storage->put($destinationTarget, file_get_contents($file));

        if (!$saved) {
            return false;
        }

        if ($uploadDisk === 'video') {
            $this->createThumbnailOfVideo($file, $destinationTarget);
        }

        return $destinationTarget;
    }

    public function createThumbnailOfVideo(UploadedFile $file, $destinationTarget)
    {
        $storage = Storage::disk(config('settings.video_thumb.disk'));
        $destinationTarget = explode('.', $destinationTarget)[0] . config('settings.video_thumb.prefix') . config('settings.video_thumb.extension');

        try {
            $ffmpeg = \FFMpeg\FFMpeg::create(config('settings.ffmpeg'));

            $video = $ffmpeg->open($file);
            $video->frame(\FFMpeg\Coordinate\TimeCode::fromSeconds(config('settings.video_thumb.time')))
                ->save($storage->getDriver()->getAdapter()->getPathPrefix() . $destinationTarget);
        } catch (\Exception $e) {
            Log::error($e);

            return false;
        }
    }

    public function destroyFile($destinationTarget, $uploadDisk = 'image')
    {
        $storage = Storage::disk($uploadDisk);

        if ($storage->exists($destinationTarget)) {
            return $storage->delete($destinationTarget);
        }
    }

    public function getTarget($path)
    {
        if (method_exists($this, 'uploadableTimestamp') && is_string($this->uploadableTimestamp())) {
            $path = Carbon::now()->format($this->uploadableTimestamp()) . '/' . $path;
        }

        if (property_exists($this, 'user') && $this->user) {
            $path = $this->user->id . '/' . $path;
        }

        return $path;
    }

    public function getFileName(UploadedFile $file)
    {
        $fileName = $file->getClientOriginalName();
        $name = str_slug(pathinfo($fileName, PATHINFO_FILENAME));
        $ext = pathinfo($fileName, PATHINFO_EXTENSION);

        return $name . '.' . $ext;
    }
}
