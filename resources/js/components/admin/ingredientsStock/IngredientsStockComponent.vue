<template>
    <button class="settings-btn w-full md:hidden flex items-center justify-center gap-2 p-2 rounded bg-primary text-white">
        <span class="capitalize">{{ $t('menu.settings_menu') }}</span>
        <i class="fa-solid fa-chevron-down text-sm"></i>
    </button>
    <div class="h-0 overflow-hidden md:h-auto md:overflow-auto transition-all duration-300">
        <nav class="db-card p-3">
            <router-link :to="{ name: 'admin.ingredients_and_stock.ingredients' }" class="db-tab-btn">
                <i class="lab lab-company text-sm"></i>
               Ingredients
            </router-link>
            <router-link :to="{ name: 'admin.ingredients_and_stock.stock_list' }" class="db-tab-btn">
                <i class="lab lab-branches text-sm"></i>
                Ingredient stock
            </router-link>
            <router-link :to="{ name: 'admin.ingredients_and_stock.purchase.ingredient' }" class="db-tab-btn">
                <i class="lab lab-company text-sm"></i>
                Purchase Ingredient
            </router-link>
            <router-link :to="{ name: 'admin.ingredients_and_stock.stock_list' }" class="db-tab-btn">
                <i class="lab lab-branches text-sm"></i>
               Item Stock
            </router-link>
            <router-link :to="{ name: 'admin.ingredients_and_stock.purchase' }" class="db-tab-btn">
                <i class="lab lab-company text-sm"></i>
                Purchase Items
            </router-link>
            <router-link :to="{ name: 'admin.settings.supplier' }" class="db-tab-btn">
                <i class="lab lab-line-supplier text-sm"></i>
                {{ $t("menu.suppliers") }}
            </router-link>
        </nav>
    </div>
</template>

<script>
export default {
    name: "IngredientsStockComponent",
};
</script>
