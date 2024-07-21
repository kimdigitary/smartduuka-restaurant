export function getName(id: number, items: { id: number, name: string }[]) {
    //@ts-ignore
    const result = items.find((item) => item.id === parseInt(id))
    return result?.name
}
export function cleanAmount(value: string) {
    return parseInt(value.replace(/\D/g, ''));
}
export function cleanAmountV2(value: string) {
    // Regular expression to match the numeric value
    const regex = /\d+(\.\d{1,2})?/;
    const match = value.match(regex);
    if (match) {
       return parseFloat(match[0]);
    }
    return 0.0;
    // return value.replace(/\D/g, '');
}
