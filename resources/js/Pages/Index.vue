<script setup>
import { ref, computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import { trans } from "laravel-vue-i18n";
import { useIntersectionObserver } from "@vueuse/core";
import axios from "axios";
import IndexLayout from "@/Layouts/IndexLayout.vue";
import SwiperSection from "@/Components/Swiper/SwiperSection.vue";
import BookCard from "@/Components/Book/BookCard.vue";
import ModalGenreEdit from "@/Components/Modals/ModalGenreEdit.vue";
import ModalGenreDelete from "@/Components/Modals/ModalGenreDelete.vue";
import IconTrash from "@icons/trash.svg?component";
import IconEdit from "@icons/edit.svg?component";

const props = defineProps({
	genres: {
		type: Object,
		required: true,
	},
});

const swiperBreakpoints = {
	0: {
		slidesPerView: 3,
		spaceBetween: 8,
	},
	640: {
		slidesPerView: 4,
		spaceBetween: 8,
	},
	768: {
		slidesPerView: 5,
		spaceBetween: 8,
	},
	1024: {
		slidesPerView: 6,
		spaceBetween: 16,
	},
	1536: {
		slidesPerView: 8,
		spaceBetween: 16,
	},
};

const $infiniteScrollingOffset = ref(null);
const genresState = ref({
	data: props.genres.data,
	meta: props.genres.meta,
});

const selectedGenre = ref(null);
const isDeleting = ref(false);
const isEditing = ref(false);

const user = computed(() => usePage().props.auth.user);
const isAdmin = user.value?.role_id === 1;

const { stop } = useIntersectionObserver(
	$infiniteScrollingOffset,
	([{ isIntersecting }]) => {
		if (!isIntersecting) {
			return;
		}

		const meta = genresState.value.meta;
		if (meta.next_cursor) {
			axios.get(`${meta.path}?cursor=${meta.next_cursor}`).then((response) => {
				genresState.value.data = [
					...genresState.value.data,
					...response.data.data,
				];
				genresState.value.meta = response.data.meta;
			});
		} else {
			stop();
		}
	}
);

const askToDeleteGenre = (genre) => {
	selectedGenre.value = genre;
	isDeleting.value = true;
};

const askToEditGenre = (genre) => {
	selectedGenre.value = genre;
	isEditing.value = true;
};
</script>

<template>
	<IndexLayout>
		<template #main>
			<SwiperSection
				v-for="(genre, indexGenre) in genresState.data"
				:key="indexGenre"
				:breakpoints="swiperBreakpoints"
			>
				<template #header>
					<div
						class="mb-4 flex items-center space-x-6 px-4 md:mb-6 md:px-6 lg:px-16"
					>
						<h2 class="index-title font-bold underline hover:text-skin-link">
							<Link :href="`/genre/${genre.slug}`">
								{{ genre.name }}
							</Link>
						</h2>
						<div class="flex space-x-1.5">
							<!-- Allow deletion of genres without books only -->
							<IconTrash
								v-if="isAdmin && !genre.books.length"
								class="w-6 cursor-pointer text-skin-danger md:w-8"
								@click="askToDeleteGenre(genre)"
							/>
							<IconEdit
								v-if="isAdmin"
								class="w-6 cursor-pointer text-skin-link md:w-8"
								@click="askToEditGenre(genre)"
							/>
						</div>
					</div>
				</template>

				<swiper-slide
					v-for="(book, indexBook) in genre.books"
					:key="indexBook"
					class="my-1 h-auto"
				>
					<BookCard :book="book" />
				</swiper-slide>
			</SwiperSection>

			<div
				ref="$infiniteScrollingOffset"
				class="-translate-y-32"
			/>
		</template>

		<template #modals>
			<ModalGenreDelete
				v-if="isAdmin"
				v-model:value="isDeleting"
			>
				<template #yesButton>
					<Link
						:href="`/genre/${selectedGenre?.id}`"
						method="delete"
						as="button"
						class="button !bg-skin-danger text-skin-white"
						:preserve-state="false"
						preserve-scroll
						@click="isDeleting = false"
					>
						{{ trans("modal.option.yes") }}
					</Link>
				</template>
			</ModalGenreDelete>

			<ModalGenreEdit
				v-if="isAdmin"
				v-model:value="isEditing"
				:preserve-scroll="false"
				:genre="selectedGenre"
			/>
		</template>
	</IndexLayout>
</template>
