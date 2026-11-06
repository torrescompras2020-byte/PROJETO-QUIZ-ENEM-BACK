
import json
from pathlib import Path

pasta = Path(__file__).parent

# Ler questões extraídas
with open(pasta / "questoes_extraidas.json", "r", encoding="utf-8") as f:
    questoes = json.load(f)

# Ler gabarito oficial
with open(pasta / "gabarito_enem_2025_caderno_1_azul.json", "r", encoding="utf-8") as f:
    gabarito = json.load(f)

respostas = gabarito["respostas_questoes_6_a_90"]
respostas_linguas = gabarito["respostas_lingua_estrangeira"]

questoes_finais = []

for questao in questoes:

    numero = str(questao["numero"])
    idioma = questao["idioma"]

    # Buscar resposta conforme o idioma
    if idioma == "ingles":
        resposta = respostas_linguas["ingles"].get(numero)

    elif idioma == "espanhol":
        resposta = respostas_linguas["espanhol"].get(numero)

    else:
        resposta = respostas.get(numero)

    if resposta is None:
        print(f"AVISO: questão {numero} ({idioma}) sem gabarito.")
        continue

    questao_final = {
        "ano": gabarito["ano"],
        "caderno": gabarito["caderno"],
        "numero": questao["numero"],
        "idioma": idioma,
        "texto": questao["texto"],
        "alternativas": questao["alternativas"],
        "resposta_correta": resposta
    }

    questoes_finais.append(questao_final)

# Salvar JSON definitivo
saida = pasta / "questoes_enem_2025_final.json"

with open(saida, "w", encoding="utf-8") as f:
    json.dump(questoes_finais, f, ensure_ascii=False, indent=2)

# Relatório
print("\nASSOCIAÇÃO CONCLUÍDA!")
print("------------------------------")
print("Total de questões:", len(questoes_finais))
print("Questões com gabarito:", sum(bool(q["resposta_correta"]) for q in questoes_finais))
print("Inglês:", sum(q["idioma"] == "ingles" for q in questoes_finais))
print("Espanhol:", sum(q["idioma"] == "espanhol" for q in questoes_finais))
print("Português:", sum(q["idioma"] == "portugues" for q in questoes_finais))
print("------------------------------")
print("Arquivo:", saida)
