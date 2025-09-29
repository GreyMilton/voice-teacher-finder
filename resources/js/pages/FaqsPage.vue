<script setup lang="ts">
import { Faq } from '@/types';

const { faqs } = defineProps<{ faqs: Faq[] }>();
</script>

<template>
  <h1>Frequently Asked Questions</h1>
  <dl>
    <template
      v-for="{ id, question, answer } in faqs"
      :key="id"
    >
      <dt>{{ question }}</dt>
      <dd>{{ answer }}</dd>
    </template>
  </dl>
</template>
