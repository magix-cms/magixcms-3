/*
 * Locale: FR (French; français)
 */
if(validate !== undefined && validate !== null) {
    validate.validators.presence.options = { message: "Ce champ est obligatoire." };
    validate.validators.length.options = {
        notValid: "Ce champ est mal rempli",
        wrongLength: "Longueur incorrecte (devrait être de %{count} caractères)",
        tooShort: "Trop court (minimum %{count} caractères)",
        tooLong: "Trop long (maximum %{count} caractères)"
    };
    validate.validators.numericality.options = {
        notValid: "Ce champ est mal rempli",
        notInteger: "Doit être un nombre entier",
        notOdd: "Doit être impair",
        notEven: "Doit être pair",
        notGreaterThan: "Doit être plus grand que %{count}",
        notGreaterThanOrEqualTo: "Doit être plus grand ou égal à %{count}",
        notEqualTo: "Doit être égal à %{count}",
        notLessThan: "Doit être plus petit que %{count}",
        notLessThanOrEqualTo: "Doit être plus petit ou égal à %{count}",
        notDivisibleBy: "Doit être divisible par %{count})"
    };
}