<script setup>
import { ref, nextTick } from "vue";
import { useForm } from "@inertiajs/vue3";
import { trans } from "laravel-vue-i18n";
import ModalContainer from "@/Components/Modals/ModalContainer.vue";
import FloatingLabel from "@/Components/Forms/FloatingLabel.vue";

const confirmingUserDeletion = ref(false);
const $passwordInput = ref(null);

const form = useForm({
	password: null,
});

const confirmUserDeletion = () => {
	confirmingUserDeletion.value = true;

	nextTick(() => $passwordInput.value.focus());
};

const deleteUser = () => {
	form.delete("/profile", {
		preserveScroll: true,
		onSuccess: () => closeModal(),
		onError: () => $passwordInput.value.focus(),
		onFinish: () => form.reset(),
	});
};

const closeModal = () => {
	confirmingUserDeletion.value = false;

	form.reset();
};
</script>

<template>
	<section class="space-y-6">
		<header>
			<h2 class="pb-3 font-means-web text-2xl font-bold">
				{{ trans("page.profile.user_delete.title") }}
			</h2>

			<p class="mt-1 text-sm text-skin-muted">
				{{ trans("page.profile.user_delete.message") }}
			</p>
		</header>

		<button
			class="button !bg-skin-danger text-skin-white"
			@click="confirmUserDeletion"
		>
			{{ trans("page.profile.user_delete.title") }}
		</button>

		<ModalContainer
			modal-id="modal-delete-account"
			:modal-title="trans('modal.profile.delete.title')"
			:show="confirmingUserDeletion"
			@close="closeModal"
		>
			<p class="mt-1 text-skin-muted">
				{{ trans("page.profile.user_delete.message") }}
			</p>

			<p class="mt-3 text-skin-muted">
				{{ trans("page.profile.user_delete.confirm") }}
			</p>

			<FloatingLabel
				ref="$passwordInput"
				v-model:value="form.password"
				class="mt-6 block w-3/4"
				input-id="password"
				:label-text="trans('common.noun.password')"
				input-type="password"
				input-autocomplete="off"
				:error-message="form.errors.password"
				is-required
				@keyup.enter="deleteUser"
			/>

			<div class="mt-6 flex justify-end space-x-4">
				<button
					class="button !bg-skin-muted text-skin-white"
					@click="closeModal"
				>
					{{ trans("common.action.cancel") }}
				</button>

				<button
					class="button !bg-skin-danger text-skin-white"
					:class="{ 'opacity-25': form.processing }"
					:disabled="form.processing"
					@click="deleteUser"
				>
					{{ trans("modal.profile.delete.option") }}
				</button>
			</div>
		</ModalContainer>
	</section>
</template>
