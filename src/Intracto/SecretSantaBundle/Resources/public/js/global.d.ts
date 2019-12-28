declare interface JQueryStatic {
    csv: {
        toArrays: (csvText: string, options?: Partial<{
            headers: boolean,
            seperator: string,
            delimiter: string,
        }>) => string[][]
    },

    smoothScroll(options: {
        scrollTarget: string
    }): unknown;
}