<script setup lang="ts">
import { Teacher } from '@/types';

const { teacher } = defineProps<{ teacher: Teacher }>();
</script>

<template>
  <h1>Teacher: {{ teacher.name }}</h1>
  <ul class="p-5">
    <li>id: {{ teacher.id }}</li>
    <li>authorisationCohort: {{ teacher.authorisationCohort }}</li>
    <li>business_email: {{ teacher.business_email }}</li>
    <li>business_phone: {{ teacher.business_phone }}</li>
    <li>business_website: {{ teacher.business_website }}</li>
    <li>countryOfOrigin: {{ teacher.countryOfOrigin }}</li>
    <li>countryOfResidence: {{ teacher.countryOfResidence }}</li>
    <li>description: {{ teacher.description }}</li>
    <li>gender: {{ teacher.gender }}</li>
    <li>gives_video_lessons: {{ teacher.gives_video_lessons }}</li>
    <li>instruments: {{ teacher.instruments.join(', ') }}</li>
    <li>languagesSung: {{ teacher.languagesSung.join(', ') }}</li>
    <li>languagesTeachesIn: {{ teacher.languagesTeachesIn.join(', ') }}</li>
    <li>name: {{ teacher.name }}</li>
    <li>profile_image_path: {{ teacher.profile_image_path }}</li>
    <li>qualification_string: {{ teacher.qualification_string }}</li>
    <li>teaches_at_cvi: {{ teacher.teaches_at_cvi }}</li>
    <li>tuitionLocations: {{ teacher.tuitionLocations.join('; ') }}</li>
    <li>updateCohorts: {{ teacher.updateCohorts.join(', ') }}</li>
  </ul>
</template>
