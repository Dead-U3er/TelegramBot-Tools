import requests
import json
import argparse
import os

# Coded by U3er
# Version -> 1.0
# https://github.com/Dead-U3er
# https://T.me/Dead_U3er
# https://T.me/Good_U3er
# https://U3er.xyz
#
# ⌜ Coded with love ⌟



os.system("cls")
os.system("clear")

GREEN = "\033[92m"
RED = "\033[91m"
RESET = "\033[0m"

valid_tokens = []

parser = argparse.ArgumentParser(description='Process some inputs.')
parser.add_argument('--type', type=str, help='Type [ WebHook , Checker ]')
parser.add_argument('--address', type=str, help='Checker mode => Address and name of tokens | Webhook => Webhook file address (url)')
parser.add_argument('--token', type=str, help='Token bot')
parser.add_argument('--type_webhook', type=str, help='Option [ Set , delete ]')
parser.add_argument('--output', type=str, help='The name of the output file')
parser.add_argument('--type_out', type=str, help='Output type')
args = parser.parse_args()

def main_check (address):
    correct_count = 0
    incorrect_count = 0
    with open(address, 'r', encoding='utf-8') as file:
        for line in file:
            line = line.strip()
            response = requests.get(f"https://api.telegram.org/bot{line}/getme")
            
            data = json.loads(response.text)
            if data.get("ok") == True:
                print(f"{GREEN}[+] " + line + f"{RESET}")
                correct_count += 1
                valid_tokens.append(line)
            else:
                print(f"{RED}[-] " + line + f"{RESET}")
                incorrect_count += 1
        
    print(f"\n{GREEN}Correct tokens: {correct_count}{RESET}")
    print(f"{RED}Incorrect tokens: {incorrect_count}{RESET}")

def output(name) :
    with open(name, 'w', encoding='utf-8') as valid_file:
        for token in valid_tokens:
            valid_file.write(token + '\n')

def type_getme(name) :
    with open(name, 'w', encoding='utf-8') as valid_file:
        for tokens in valid_tokens:
            token = tokens.strip()
            getme = requests.get(f"https://api.telegram.org/bot{token}/getme")
            data = json.loads(getme.text)
            if data.get("ok") == True :
                id_bot = data["result"]["id"]
                first_name = data["result"]["first_name"]
                username = data["result"]["username"]
                can_join_groups = data["result"]["can_join_groups"]
                can_read_all_group_messages = data["result"]["can_read_all_group_messages"]
                supports_inline_queries = data["result"]["supports_inline_queries"]
                can_connect_to_business = data["result"]["can_connect_to_business"]
            
            valid_file.write(
f"""# ------------------------------ #
{tokens}
ID Bot : {id_bot}
First name : {first_name}
Username : @{username}
Can join groups : {can_join_groups}
Can read all group messages : {can_read_all_group_messages}
Supports inline queries : {supports_inline_queries}
Can connect to business : {can_connect_to_business}
# ------------------------------ #
\n\n
""")
            
def type_webhook(name) :
    with open(name, 'w', encoding='utf-8') as valid_file:
        for tokens in valid_tokens:
            token = tokens.strip()
            getme = requests.get(f"https://api.telegram.org/bot{token}/getWebhookInfo")
            data = json.loads(getme.text)
            if data.get("ok") == True :
                url = data["result"]["url"]
                if url == "" :
                    url = "Empty"
                has_custom_certificate = data["result"]["has_custom_certificate"]
                pending_update_count = data["result"]["pending_update_count"]
            
            valid_file.write(
f"""# ------------------------------ #
{tokens}
URL : {url}
Has custom certificate : {has_custom_certificate}
Pending update count : {pending_update_count}
# ------------------------------ #
\n\n
""")

def deletewebhook(token_bot):
    response = requests.get(f"https://api.telegram.org/bot{token_bot}/deletewebhook")
    if response.status_code == 200:
        data = json.loads(response.text)
        if data.get("ok") == True:
            dec = data["description"]
            print(f"description : {dec}")
        else:
            print(f"Failed to delete webhook: {data}")
    else:
        print(f"HTTP request failed with status code: {response.status_code}")
            
def setwebhook(token, url):
    response = requests.get(f"https://api.telegram.org/bot{token}/setwebhook?url={url}")
    if response.status_code == 200:
        data = json.loads(response.text)
        if data.get("ok") == True:
            dec = data["description"]
            print(f"description : {dec} \nURL : {url}")
        else:
            print(f"Failed to set webhook: {data}")
    else:
        print(f"HTTP request failed with status code: {response.status_code}")


if args.type.lower() == "checker" :
    main_check(args.address)
    if args.type_out :
        if args.type_out.lower() == "getme" :
            type_getme(args.output)
        elif args.type_out.lower() == "webhook" :
            type_webhook(args.output)
        else :
            output(args.output)
elif args.type.lower() == "webhook" :
    if args.type_webhook.lower() == "set" :
        setwebhook(args.token,args.address)
    elif args.type_webhook.lower() == "delete" :
        deletewebhook(args.token)


# Coded by U3er
# Version -> 1.0
# https://github.com/Dead-U3er
# https://T.me/Dead_U3er
# https://T.me/Good_U3er
# https://U3er.xyz
#
# ⌜ Coded with love ⌟