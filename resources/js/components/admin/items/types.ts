import {defineProps} from "vue";

type props = {
    form: {
        name: string;
        price: string;
        description: string;
        caution: string;
        is_featured: number; // askEnum
        is_stockable: number; // askEnum
        buying_price: number;
        item_type: number; // itemTypeEnum
        item_category_id: number | null;
        tax_id: number | null;
        status: number; // statusEnum
    };
    search: {
        paginate: number;
        page: number;
        per_page: number;
        order_column: string;
        order_type: string;
        name: string;
        price: string;
        item_category_id: number | null;
        status: number | null; // statusEnum
        tax_id: number | null;
        item_type: number | null; // itemTypeEnum
        is_featured: number | null; // askEnum
    };
};
export const TItem = defineProps<props>();
