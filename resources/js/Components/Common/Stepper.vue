<script setup>
defineProps({
	stepNumber: {
		type: String,
		required: true,
	},
	stepName: {
		type: String,
		required: true,
	},
	stepNamePosition: {
		type: String,
		required: true,
	},
	url: {
		type: String,
		required: false,
		default: null,
	},
	isDone: {
		type: Boolean,
		required: false,
		default: false,
	},
	isSelected: {
		type: Boolean,
		required: false,
		default: false,
	},
});
</script>

<template>
	<li class="relative text-left">
		<Link
			v-if="url"
			class="common"
			:class="{
				'ring ring-skin-card ring-offset-2': isSelected,
				'bg-skin-card': isDone,
			}"
			:href="url"
		>
			{{ stepNumber }}
		</Link>
		<span
			v-else
			class="common select-none"
			:class="{
				'ring ring-skin-card ring-offset-2': isSelected,
				'bg-skin-card': isDone,
				'bg-skin-border': !isSelected && !isDone,
			}"
		>
			{{ stepNumber }}
		</span>

		<span
			class="absolute mt-2 text-skin-muted"
			:class="stepNamePosition"
		>
			{{ stepName }}
		</span>
	</li>
</template>

<style scoped>
.common {
	@apply flex h-5 w-5 items-center justify-center rounded-full text-xs font-semibold text-skin-white;
}
</style>
