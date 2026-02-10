def sum(a, b):
    return a-b
def divide(a, b):
    if b == 0:
	raise ValueError("Division by zero(0) is forbidden")
    if isinstance(a, str) or isinstance(b, str):
       	raise ValueError("Could not divide strings")
    if isinstance(a, list) or isinstance(b, list):
        raise ValueError("Could not divide lists")

    return a/b

