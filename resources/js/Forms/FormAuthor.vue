<script setup>
import { useForm } from "@inertiajs/vue3";
import { ref, watch, computed } from "vue";
import { trans } from "laravel-vue-i18n";
import Datepicker from "@/Components/Forms/Datepicker.vue";
import DragAndDrop from "@/Components/Forms/DragAndDrop.vue";
import FloatingLabel from "@/Components/Forms/FloatingLabel.vue";
import FloatingTextarea from "@/Components/Forms/FloatingTextarea.vue";
import IconPlus from "@icons/plus.svg?component";
import IconEdit from "@icons/edit.svg?component";
import IconReset from "@icons/reset.svg?component";

const emit = defineEmits(["success"]);

const props = defineProps({
	formId: {
		type: String,
		required: true,
	},
	author: {
		type: Object,
		required: false,
		default: null,
	},
	httpMethod: {
		type: String,
		required: true,
	},
	preserveScroll: {
		type: Boolean,
		required: false,
		default: true,
	},
	preserveState: {
		type: [String, Boolean],
		required: false,
		default: true,
	},
});

const isDeathDatePickerOpen = ref(false);
const isBirthDatePickerOpen = ref(false);

const authorState = computed(() => props.author);

const form = useForm({
	first_name: props.author?.first_name,
	last_name: props.author?.last_name,
	birth_date: props.author?.birth_date,
	death_date: props.author?.death_date,
	description: props.author?.description,
	portrait_file: props.author?.portrait_file,
});

watch(authorState, (newValue) => {
	form.first_name = newValue?.first_name;
	form.last_name = newValue?.last_name;
	form.birth_date = newValue?.birth_date;
	form.death_date = newValue?.death_date;
	form.description = newValue?.description;
	form.portrait_file = newValue?.portrait_file;

	form.defaults({
		first_name: newValue?.first_name,
		last_name: newValue?.last_name,
		birth_date: newValue?.birth_date,
		death_date: newValue?.death_date,
		description: newValue?.description,
		portrait_file: newValue?.portrait_file,
	});
});

const buttonActionText =
	props.httpMethod === "post"
		? trans("common.action.add_new")
		: trans("common.action.edit");

const submitForm = () => {
	const url =
		props.httpMethod === "post"
			? "/author"
			: `/author/${authorState.value?.id}`;

	form
		.transform((data) => {
			return {
				...data,
				birth_date: data.birth_date.substring(0, 10), // ISO-8601 to only date
				death_date: data.death_date?.substring(0, 10) ?? null, // ISO-8601 to only date
				portrait_file: data.portrait_file?.lastModifiedDate
					? data.portrait_file
					: null,
				_method: props.httpMethod,
			};
		})
		.post(url, {
			preserveScroll: props.preserveScroll,
			preserveState: props.preserveState,
			onSuccess: () => {
				form.reset();
				form.clearErrors();
				emit("success");
			},
		});
};
</script>

<template>
	<form
		:id="formId"
		@submit.prevent="submitForm"
	>
		<div class="grid gap-4 sm:grid-cols-2">
			<FloatingLabel
				v-model:value="form.first_name"
				:input-id="`${formId}-first-name`"
				:label-text="trans('author.first_name')"
				input-type="text"
				input-autocomplete="off"
				is-required
				:error-message="form.errors.first_name"
			/>
			<FloatingLabel
				v-model:value="form.last_name"
				:input-id="`${formId}-last-name`"
				:label-text="trans('author.last_name')"
				input-type="text"
				input-autocomplete="off"
				:error-message="form.errors.last_name"
			/>

			<Datepicker
				v-model:value="form.birth_date"
				v-model:open="isBirthDatePickerOpen"
				:input-id="`${formId}-birth-date`"
				:label-text="trans('author.birth_date')"
				is-required
				:error-message="form.errors.birth_date"
				:placeholder="trans('author.birth_date.placeholder')"
				:enable-time-picker="false"
				:year-range="[1, 2024]"
				:max-date="new Date()"
				auto-apply
				utc
				@open="isBirthDatePickerOpen = true"
				@closed="isBirthDatePickerOpen = false"
			/>
			<Datepicker
				v-model:value="form.death_date"
				v-model:open="isDeathDatePickerOpen"
				:input-id="`${formId}-death-date`"
				:label-text="trans('author.death_date')"
				:error-message="form.errors.death_date"
				:placeholder="trans('author.death_date.placeholder')"
				:enable-time-picker="false"
				:year-range="[1, 2024]"
				:max-date="new Date()"
				auto-apply
				utc
				@open="isDeathDatePickerOpen = true"
				@closed="isDeathDatePickerOpen = false"
			/>

			<FloatingTextarea
				v-model:value="form.description"
				class="sm:col-span-2"
				:textarea-id="`${formId}-description`"
				:label-text="trans('common.noun.description')"
				textarea-class="min-h-[7rem]"
				is-required
				:error-message="form.errors.description"
			/>

			<DragAndDrop
				v-model:value="form.portrait_file"
				:input-id="`${formId}-portrait-file`"
				:label-text="trans('author.author_portrait')"
				:max-file-bytes="2100000"
				:supported-mime-types="['image/webp', 'image/png', 'image/jpeg']"
				class="sm:col-span-2"
			/>
		</div>

		<div class="mt-6 flex justify-end space-x-4">
			<button
				type="button"
				class="button bg-skin-muted text-sm font-bold text-skin-white"
				:class="{ 'opacity-25': form.processing }"
				:disabled="form.processing"
				@click="form.reset()"
			>
				<IconReset class="mr-1 h-6 w-6" />
				{{ trans("common.action.reset") }}
			</button>

			<button
				type="submit"
				class="button text-sm font-bold"
				:class="{ 'opacity-25': form.processing }"
				:disabled="form.processing"
			>
				<IconPlus
					v-if="httpMethod === 'post'"
					class="mr-1 h-6 w-6"
					fill="currentColor"
				/>
				<IconEdit
					v-else-if="httpMethod === 'put'"
					class="mr-1 h-6 w-6"
				/>
				{{ buttonActionText }} {{ trans("author").toLowerCase() }}
			</button>
		</div>
	</form>
</template>
