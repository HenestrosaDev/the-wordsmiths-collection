<script setup>
import { ref, watch, computed } from "vue";
import { useForm } from "@inertiajs/vue3";
import { trans } from "laravel-vue-i18n";
import Datepicker from "@/Components/Forms/Datepicker.vue";
import FloatingLabel from "@/Components/Forms/FloatingLabel.vue";
import FloatingLabelSelect from "@/Components/Forms/FloatingLabelSelect.vue";
import IconPlus from "@icons/plus.svg?component";
import IconEdit from "@icons/edit.svg?component";
import IconReset from "@icons/reset.svg?component";

const emit = defineEmits(["success"]);

const props = defineProps({
	formId: {
		type: String,
		required: true,
	},
	user: {
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

const isStartDatePickerOpen = ref(false);
const isEndDatePickerOpen = ref(false);

const userState = computed(() => props.user);

const form = useForm({
	username: props.user?.username,
	email: props.user?.email,
	password: "",
	role_id: props.user?.role.id,
	subscription_plan_id: props.user?.subscription?.plan?.id,
	status: props.user?.subscription.status,
	start_date: props.user?.subscription.start_date,
	end_date: props.user?.subscription.end_date,
});

watch(userState, (newValue) => {
	form.username = newValue?.username;
	form.email = newValue?.email;
	form.password = newValue?.password;
	form.role_id = newValue?.role.id;
	form.subscription_plan_id = newValue?.subscription?.plan?.id;
	form.status = newValue?.subscription.status;
	form.start_date = newValue?.subscription.start_date;
	form.end_date = newValue?.subscription.end_date;

	form.defaults({
		username: newValue?.username,
		email: newValue?.email,
		password: newValue?.password,
		role_id: newValue?.role.id,
		subscription_plan_id: props.user?.subscription?.plan?.id,
		status: newValue?.subscription.status,
		start_date: newValue?.subscription.start_date,
		end_date: newValue?.subscription.end_date,
	});
});

const buttonActionText =
	props.httpMethod === "post"
		? trans("common.action.add_new")
		: trans("common.action.edit");

const submitForm = () => {
	const url = props.httpMethod === "post" ? "/user" : `/user/${props.user?.id}`;

	form
		.transform((data) => ({
			...data,
			_method: props.httpMethod,
			end_date: data.end_date.substring(0, 10) ?? null, // ISO-8601 to only date
		}))
		[props.httpMethod](url, {
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
				v-model:value="form.username"
				:input-id="`${formId}-username`"
				:label-text="trans('common.noun.username')"
				input-type="text"
				input-autocomplete="off"
				is-required
				:error-message="form.errors.username"
			/>
			<FloatingLabel
				v-model:value="form.email"
				:input-id="`${formId}-email`"
				:label-text="trans('common.noun.email')"
				input-type="text"
				input-autocomplete="off"
				is-required
				:error-message="form.errors.email"
			/>

			<FloatingLabel
				v-model:value="form.password"
				input-id="password"
				:label-text="trans('common.noun.password')"
				input-type="password"
				input-autocomplete="new-password"
				:error-message="form.errors.password"
			/>
			<FloatingLabelSelect
				v-model:value="form.role_id"
				:select-id="`${formId}-role`"
				:label-text="trans('user.role')"
				:options="[
					{ id: '1', name: trans('user.role.admin') },
					{ id: '2', name: trans('user.role.user') },
				]"
				is-required
			/>

			<FloatingLabelSelect
				v-model:value="form.subscription_plan_id"
				:select-id="`${formId}-subscription-type`"
				:label-text="trans('subscription.type')"
				:options="[
					{ id: '1', name: 'Premium' },
					{ id: '2', name: 'Basic' },
				]"
				is-required
			/>
			<FloatingLabelSelect
				v-model:value="form.status"
				:select-id="`${formId}-status`"
				:label-text="trans('user.status')"
				:options="[
					{ id: 'active', name: trans('user.status.active') },
					{ id: 'canceled', name: trans('user.status.canceled') },
				]"
				is-required
			/>

			<Datepicker
				v-model:value="form.start_date"
				v-model:open="isStartDatePickerOpen"
				:input-id="`${formId}-start-date`"
				:label-text="trans('subscription.start_date')"
				is-required
				:error-message="form.errors.start_date"
				:placeholder="trans('subscription.start_date.placeholder')"
				:enable-time-picker="false"
				disabled
				utc
				@open="isStartDatePickerOpen = true"
				@closed="isStartDatePickerOpen = false"
			/>
			<Datepicker
				v-model:value="form.end_date"
				v-model:open="isEndDatePickerOpen"
				:input-id="`${formId}-end-date`"
				:label-text="trans('subscription.end_date')"
				:error-message="form.errors.end_date"
				:placeholder="trans('subscription.end_date.placeholder')"
				:enable-time-picker="false"
				:min-date="new Date()"
				:teleport="true"
				auto-apply
				utc
				@open="isEndDatePickerOpen = true"
				@closed="isEndDatePickerOpen = false"
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
				{{ buttonActionText }} {{ trans("user").toLowerCase() }}
			</button>
		</div>
	</form>
</template>
