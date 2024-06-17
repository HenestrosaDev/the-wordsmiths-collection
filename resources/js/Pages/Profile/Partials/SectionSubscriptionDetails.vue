<script setup>
import { ref, computed } from "vue";
import { trans } from "laravel-vue-i18n";
import ModalContainer from "@/Components/Modals/ModalContainer.vue";

const props = defineProps({
	subscription: {
		type: Object,
		required: true,
	},
	otherSubscriptionPlan: {
		type: Object,
		required: true,
	},
});

const isCancelSubscriptionModalOpen = ref(false);
const isChangePlanModalOpen = ref(false);

const isSubscriptionCanceled = computed(
	() => props.subscription.status === "canceled"
);
const isSubscriptionActive = computed(
	() => props.subscription.status === "active"
);

const fromBasicToPremium = computed(() => props.otherSubscriptionPlan.id == 1);
const fromPremiumToBasic = computed(() => props.otherSubscriptionPlan.id == 2);

const startDate = new Date(props.subscription.start_date);
const formattedStartDate = startDate.toLocaleDateString();

const endDate = new Date(props.subscription.end_date);
const formattedEndDate = endDate.toLocaleDateString();

// PRORATED FINAL PRICE CALCULATION

const MILLS_IN_ONE_DAY = 24 * 60 * 60 * 1000; // hours * minutes * seconds * milliseconds
const daysUntilNextPayment = Math.round(
	Math.abs((Date.now() - endDate) / MILLS_IN_ONE_DAY)
);

const dailyPriceCurrentPlan =
	props.subscription.plan.price / props.subscription.plan.duration_days;
const dailyPriceOtherPlan =
	props.otherSubscriptionPlan.price / props.otherSubscriptionPlan.duration_days;

// Replace the price of the remaining number of days in the Basic plan with its equivalent in the Premium plan.
// For example, if 10 days of Basic plan is 0,49€ and 10 days of Premium plan is 1,00€, then the client will be charged 1,00 - 0,49 = 0,51€.
const proratedPriceCurrentPlan = dailyPriceCurrentPlan * daysUntilNextPayment;
const proratedPriceOtherPlan = dailyPriceOtherPlan * daysUntilNextPayment;

// Round to two decimals and display two decimals even if the result has only one.
const proratedFinalPrice = Math.round(
	Math.abs((proratedPriceCurrentPlan - proratedPriceOtherPlan) * 100) / 100
).toFixed(2);
</script>

<template>
	<section class="space-y-6">
		<header>
			<h2 class="font-means-web text-2xl font-bold">
				{{ trans("page.profile.subscription_details.title") }}
			</h2>
		</header>

		<div>
			<h3 class="text-lg">
				{{
					trans("page.profile.subscription_details.current_plan", {
						plan: subscription.plan.name,
					})
				}}
			</h3>
			<p>
				<span class="font-bold">{{ trans("user.status") }}:</span>{{}}
				<span class="capitalize">{{
					trans(`user.status.${subscription.status}`)
				}}</span>
			</p>
			<p>
				<span class="font-bold">
					{{ trans("page.profile.subscription_details.active_since") }}:
				</span>
				{{ formattedStartDate }}
			</p>
			<p v-if="isSubscriptionCanceled">
				<span class="font-bold">
					{{ trans("page.profile.subscription_details.active_until") }}:
				</span>
				{{ formattedEndDate }}
			</p>
			<p v-if="isSubscriptionActive">
				<span class="font-bold">
					{{ trans("page.profile.subscription_details.next_payment_date") }}:
				</span>
				{{ formattedEndDate }}
			</p>
		</div>

		<div class="sm:flex w-fit space-x-3">
			<button
				v-if="isSubscriptionActive"
				class="button"
				@click="isChangePlanModalOpen = true"
			>
				{{ trans("page.profile.subscription_details.change_plan") }}
			</button>

			<Link
				v-if="isSubscriptionCanceled"
				href="/subscription/reactivate"
				method="post"
				as="button"
				class="button !bg-skin-success text-skin-white"
				preserve-scroll
			>
				{{ trans("page.profile.subscription_details.reactivate_subscription") }}
			</Link>

			<button
				v-if="isSubscriptionActive"
				class="button !bg-skin-danger text-skin-white"
				@click="isCancelSubscriptionModalOpen = true"
			>
				{{ trans("page.profile.subscription_details.cancel_subscription") }}
			</button>
		</div>

		<ModalContainer
			v-if="isSubscriptionActive"
			modal-id="modal-cancel-subscription"
			:modal-title="trans('modal.subscription.cancel.title')"
			:show="isCancelSubscriptionModalOpen"
			@close="isCancelSubscriptionModalOpen = false"
		>
			<div class="space-y-2">
				<p>
					{{
						trans("modal.subscription.cancel.p1", { date: formattedEndDate })
					}}
				</p>
				<p>{{ trans("modal.subscription.cancel.p2") }}</p>
				<p>{{ trans("modal.subscription.cancel.p3") }}</p>
			</div>

			<div class="mt-6 flex justify-end space-x-4">
				<button
					class="button !bg-skin-muted text-skin-white"
					@click="isCancelSubscriptionModalOpen = false"
				>
					{{ trans("modal.option.no") }}
				</button>

				<Link
					href="/subscription/cancel"
					method="post"
					as="button"
					class="button !bg-skin-danger text-skin-white"
					preserve-scroll
					@click="isCancelSubscriptionModalOpen = false"
				>
					{{ trans("modal.option.yes") }}
				</Link>
			</div>
		</ModalContainer>

		<ModalContainer
			v-if="isSubscriptionActive"
			modal-id="modal-change-subscription"
			:modal-title="trans('modal.subscription.change.title')"
			:show="isChangePlanModalOpen"
			@close="isChangePlanModalOpen = false"
		>
			<div>
				<div class="rounded-t-md border-b border-skin-border bg-skin-white p-3">
					<p class="mb-2 text-sm font-bold uppercase">
						{{ trans("modal.subscription.change.current_plan") }}
					</p>
					<p>
						{{ subscription.plan.name }}: {{ subscription.plan.price }}
						{{ subscription.plan.currency }}
						{{ trans("modal.subscription.change.month") }}
					</p>
				</div>

				<div class="rounded-b-md bg-skin-white p-3">
					<p class="mb-2 text-sm font-bold uppercase">
						{{ trans("modal.subscription.change.new_plan") }}
					</p>
					<p>
						{{ otherSubscriptionPlan.name }}:
						{{ otherSubscriptionPlan.price }}
						{{ otherSubscriptionPlan.currency }}
						{{ trans("modal.subscription.change.month") }}
					</p>
				</div>

				<div class="mt-4 space-y-2">
					<p>
						{{
							trans("modal.subscription.change.your_new_plan_starts_now", {
								price: otherSubscriptionPlan.price,
								currency: otherSubscriptionPlan.currency,
								date: formattedEndDate,
							})
						}}
					</p>

					<p v-if="fromBasicToPremium">
						{{
							trans("modal.subscription.change.from_basic_to_premium", {
								remainderPrice: proratedFinalPrice,
								remainderCurrency: subscription.plan.currency,
								days: daysUntilNextPayment,
								premiumPrice: otherSubscriptionPlan.price,
								premiumCurrency: otherSubscriptionPlan.currency,
							})
						}}
					</p>
					<p v-if="fromPremiumToBasic">
						{{ trans("modal.subscription.change.from_premium_to_basic") }}
					</p>

					<!-- eslint-disable vue/no-v-html  -->
					<p v-html="trans('modal.subscription.change.agreement')" />
					<!-- eslint-enable -->
				</div>
			</div>

			<div class="mt-6 flex justify-end space-x-4">
				<button
					class="button !bg-skin-muted text-skin-white"
					@click="isChangePlanModalOpen = false"
				>
					{{ trans("common.action.cancel") }}
				</button>

				<Link
					:href="`/subscription/update/${otherSubscriptionPlan.id}`"
					method="patch"
					as="button"
					class="button"
					preserve-scroll
					@click="isChangePlanModalOpen = false"
				>
					{{ trans("common.action.confirm") }}
				</Link>
			</div>
		</ModalContainer>
	</section>
</template>
