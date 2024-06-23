export function getName(id: number, items: { id: number, name: string }[]) {
    //@ts-ignore
    const result = items.find((item) => item.id === parseInt(id))
    return result?.name
}
export function cleanAmount(value: string) {
    return value.replace(/\D/g, '');
}
