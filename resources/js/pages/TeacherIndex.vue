<script setup lang="ts">
import { Teacher } from '@/types';

const { teachers } = defineProps<{ teachers: Teacher[] }>();
</script>

<template>
  <h1>Find a Teacher</h1>
  <ul>
    <li
      v-for="{ id, name } in teachers"
      :key="id"
    >
      {{ name }}
    </li>
  </ul>
</template>
