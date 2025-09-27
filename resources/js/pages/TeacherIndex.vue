<script setup lang="ts">
import { Teacher } from '@/types';

const { teachers } = defineProps<{ teachers: Teacher[] }>();
</script>

<template>
  <h1>Find a Teacher</h1>
  <ul>
    <li
      v-for="teacher in teachers"
      :key="teacher.id"
    >
      {{ teacher.name }}
    </li>
  </ul>
</template>
