<script setup>
import LocaleSwitcher from "@/Components/Common/LocaleSwitcher.vue";
import { trans } from "laravel-vue-i18n";

const socials = [
	{
		title: "GitHub profile",
		link: "https://github.com/henestrosadev",
		iconName: "brand-github.svg",
	},
	{
		title: "LinkedIn",
		link: "https://www.linkedin.com/in/henestrosadev/",
		iconName: "brand-linkedin.svg",
	},
	{
		title: "Mail",
		link: "mailto:henestrosadev@gmail.com",
		iconName: "mail.svg",
	},
];
</script>

<template>
	<div
		class="sticky top-[100vh] mx-auto flex w-full items-center justify-center bg-skin-tertiary text-skin-primary"
	>
		<footer class="py-10 text-center">
			<div class="mb-4 mt-2 flex justify-center space-x-12">
				<!-- eslint-disable vue/no-v-html -->
				<a
					v-for="(social, index) in socials"
					:key="index"
					class="link-outline scale-150 select-none invert hover:rotate-12"
					target="_blank"
					rel="noopener"
					:title="social.title"
					:href="social.link"
				>
					<img
						:src="`/images/icons/${social.iconName}`"
						:alt="social.title"
						class="h-6 w-6"
					/>
				</a>
			</div>
			<p className="mt-5 font-bold">The Wordsmith's Collection &copy; 2024</p>
			<p>
				<span>{{ trans("component.footer.no_tracking_or_ads") }}</span> |
				<Link
					:href="trans('terms_and_conditions.url_path')"
					class="underline hover:text-skin-link"
				>
					{{ trans("common.noun.terms_and_conditions") }}
				</Link>
			</p>
			<LocaleSwitcher />
		</footer>
	</div>
</template>
