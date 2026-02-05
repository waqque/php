def sum(a, b):
    return a+b
def divide(a, b):
    if b == 0:
	raise ValueError("Division by zero is forbidden")
    if isinstance(a, str) or isinstance(b, str):
       	raise ValueError("Could not divide strings")
    return a/b

