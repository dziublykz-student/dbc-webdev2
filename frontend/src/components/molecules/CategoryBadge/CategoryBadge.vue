<template>
  <Badge :variant="categoryVariant" size="sm">
    {{ category }}
  </Badge>
</template>

<script setup>
import { computed } from 'vue';
import Badge from '../../atoms/Badge/Badge.vue';

const props = defineProps({
  category: {
    type: String,
    required: true,
  },
});

const categoryVariant = computed(() => {
  const variantMap = {
    Petrol: 'warning',
    Diesel: 'secondary',
    Electric: 'success',
    Hybrid: 'primary',
  };

  return variantMap[props.category] || 'default';
});
</script>