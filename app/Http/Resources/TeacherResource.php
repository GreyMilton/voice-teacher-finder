<?php

namespace App\Http\Resources;

use App\Models\TuitionLocation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'authorisationCohort' => $this->authorisationCohort?->title,
            'business_email' => $this->business_email,
            'business_phone' => $this->business_phone,
            'business_website' => $this->business_website,
            'countryOfOrigin' => $this->countryOfOrigin?->english_name,
            'countryOfResidence' => $this->countryOfResidence?->english_name,
            'description' => $this->description,
            'gender' => $this->gender,
            'gives_video_lessons' => $this->gives_video_lessons,
            'instruments' => $this->instruments?->pluck('name'),
            'languagesSung' => $this->languagesSung?->pluck('english_name'),
            'languagesTeachesIn' => $this->languagesTeachesIn?->pluck('english_name'),
            'name' => $this->name,
            'profile_image_path' => $this->profile_image_path,
            'qualification_string' => $this->qualification_string,
            'teaches_at_cvi' => $this->teaches_at_cvi,
            'tuitionLocations' => $this->tuitionLocations?->map(
                fn (TuitionLocation $location) => $location->title,
            ),
            'updateCohorts' => $this->updateCohorts?->pluck('course_date'),
        ];
    }
}
