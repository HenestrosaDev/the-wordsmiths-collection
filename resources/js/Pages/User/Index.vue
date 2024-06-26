<script setup>
import { ref, watch } from "vue";
import { router } from "@inertiajs/vue3";
import { trans, transChoice } from "laravel-vue-i18n";
import { useDebounceFn } from "@vueuse/core";
import BaseLayout from "@/Layouts/BaseLayout.vue";
import AppHead from "@/Components/Common/AppHead.vue";
import Nav from "@/Components/Common/Nav.vue";
import InputWithIcon from "@/Components/Forms/InputWithIcon.vue";
import Table from "@/Components/Common/Table.vue";
import Pagination from "@/Components/Common/Pagination.vue";
import UserRow from "@/Components/User/UserRow.vue";
import Footer from "@/Components/Common/Footer.vue";
import IconSearch from "@icons/search.svg?component";
import ModalContainer from "@/Components/Modals/ModalContainer.vue";
import FormUser from "@/Forms/FormUser.vue";

const props = defineProps({
	users: {
		type: Object,
		required: true,
	},
	userCount: {
		type: Number,
		required: true,
	},
	displayRange: {
		type: String,
		required: true,
	},
	filters: {
		type: Object,
		required: false,
		default: null,
	},
});

const isDeleting = ref(false);
const isEditing = ref(false);

const search = ref(props.filters.search);
watch(
	search,
	useDebounceFn((value) => {
		// run the function every 500 milliseconds
		// .get() makes an HTTP GET request
		router.get(
			"/users",
			{ ...(value !== "" ? { search: value } : null) },
			{
				preserveState: true,
				replace: true, // avoids adding new entries into the browser history for each search we make
			}
		);
	}, 500)
);

const selectedUser = ref(null);

const askToDeleteUser = (user) => {
	selectedUser.value = user;
	isDeleting.value = true;
};

const askToEditUser = (user) => {
	selectedUser.value = user;
	isEditing.value = true;
};

const tableColumns = [
	trans("ID"),
	trans("user"),
	trans("Subscription plan"),
	trans("subscription.start_date"),
	trans("subscription.end_date"),
	trans("user.status"),
	trans("user.role"),
	trans("common.action.actions"),
];
</script>

<template>
	<AppHead :title="trans('users')" />

	<BaseLayout>
		<Nav />

		<div class="page-container">
			<main class="mx-auto mb-20 max-w-7xl sm:mb-32">
				<div class="mx-6 sm:mx-8 md:mx-10 lg:mx-12">
					<header class="mx-auto pt-12 text-center">
						<h2 class="section-title underline">{{ trans("users") }}</h2>
					</header>

					<div class="space-y-6 py-12">
						<div
							class="block w-full space-y-2.5 md:flex md:items-center md:justify-between md:space-y-0"
						>
							<p>
								{{
									transChoice("page.user.index.display_range", userCount, {
										range: displayRange,
										total: userCount,
									})
								}}
							</p>
							<InputWithIcon
								v-model:value="search"
								input-type="text"
								input-autocomplete="off"
								:input-placeholder="`${trans('common.action.search')}...`"
								:icon-aria-label="trans('common.action.search')"
								class="w-full md:max-w-sm"
							>
								<IconSearch
									class="h-4 w-4 text-skin-muted"
									aria-hidden="true"
									fill="none"
								/>
							</InputWithIcon>
						</div>

						<Table :columns="tableColumns">
							<UserRow
								v-for="(user, index) in users.data"
								:key="index"
								:user="user"
								@edit="askToEditUser(user)"
								@delete="askToDeleteUser(user)"
							/>
						</Table>

						<Pagination
							v-if="users.links"
							class="flex justify-center"
							:links="users.links"
						/>
					</div>
				</div>
			</main>

			<Footer />
		</div>

		<ModalContainer
			modal-id="modal-delete-user"
			:modal-title="trans('modal.user.delete.title')"
			:show="isDeleting"
			@close="isDeleting = false"
		>
			<div class="space-y-2">
				<p>
					{{ trans("modal.user.delete.message") }}
				</p>
			</div>

			<div class="mt-6 flex justify-end space-x-4">
				<button
					class="button !bg-skin-muted text-skin-white"
					@click="isDeleting = false"
				>
					{{ trans("modal.option.no") }}
				</button>

				<Link
					:href="`/user/${selectedUser?.id}`"
					method="delete"
					as="button"
					class="button !bg-skin-danger text-skin-white"
					:preserve-state="false"
					@click="isDeleting = false"
				>
					{{ trans("modal.option.yes") }}
				</Link>
			</div>
		</ModalContainer>

		<ModalContainer
			modal-id="modal-edit-user"
			:modal-title="trans('modal.user.edit.title')"
			:show="isEditing"
			@close="isEditing = false"
		>
			<FormUser
				form-id="user-edit"
				:user="selectedUser"
				http-method="put"
				:preserve-scroll="false"
				preserve-state="errors"
			/>
		</ModalContainer>
	</BaseLayout>
</template>
