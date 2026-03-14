import React, { useState } from 'react';
import { useMutation } from '@apollo/client';
import { PLACE_ORDER, type PlaceOrderData } from '../graphQl/queries';
import type { CartItem } from '../types';
import Toast from './Toast';

interface ToastState {
  message: string;
  type: 'success' | 'error';
}

interface CartOverlayProps {
  isOpen: boolean;
  onClose: () => void;
  cartItems: CartItem[];
  decreaseQuantity: (index: number) => void;
  increaseQuantity: (index: number) => void;
  clearCart: () => void;
}

export default function CartOverlay({
  isOpen,
  onClose,
  cartItems,
  decreaseQuantity,
  increaseQuantity,
  clearCart,
}: CartOverlayProps) {
  const [toast, setToast] = useState<ToastState | null>(null);
  const totalQuantity = cartItems.reduce((sum, item) => sum + item.quantity, 0);
  const total = cartItems.reduce((sum, item) => sum + item.price * item.quantity, 0);

  const [placeOrder] = useMutation<PlaceOrderData>(PLACE_ORDER);

  const handlePlaceOrder = async (): Promise<void> => {
    try {
      await placeOrder({
        variables: {
          items: cartItems.map((item) => ({
            name: item.name,
            price: item.price,
            quantity: item.quantity,
            selectedAttributes: JSON.stringify(item.selectedAttributes),
          })),
          totalPrice: total,
        },
      });

      localStorage.removeItem('cartItems');
      setToast({ message: 'Order placed successfully!', type: 'success' });
      clearCart();
    } catch {
      setToast({ message: 'Order failed. Please try again.', type: 'error' });
    }
  };

  if (!isOpen) return (
    <>
      {toast && <Toast message={toast.message} type={toast.type} onClose={() => setToast(null)} />}
    </>
  );

  return (
    <>
      {toast && <Toast message={toast.message} type={toast.type} onClose={() => setToast(null)} />}

      <div
        className="fixed inset-0 top-14 sm:top-16 z-40 bg-black bg-opacity-50"
        onClick={onClose}
      />

      <div
        data-testid="cart-overlay"
        className="fixed top-14 sm:top-16 right-0 z-50 bg-white shadow-lg w-80 max-h-[calc(100vh-4rem)] overflow-y-auto p-4"
      >
        <h2 className="text-sm font-bold mb-4">
          My Bag,{' '}
          <span className="font-normal">
            {totalQuantity === 1 ? '1 Item' : `${totalQuantity} Items`}
          </span>
        </h2>

        {cartItems.length === 0 ? (
          <p className="text-sm text-gray-500">Your cart is empty.</p>
        ) : (
          <ul className="space-y-6">
            {cartItems.map((item, index) => (
              <li key={index} className="flex gap-2 items-stretch">
                <div className="flex-1 min-w-0">
                  <h3 className="font-light text-sm mb-0.5">{item.name}</h3>
                  <p className="text-sm font-medium mb-2">${item.price?.toFixed(2)}</p>
                  {item.attributes.map((attr) => {
                    const attrName = attr.name.toLowerCase().replace(/[^a-z0-9]+/g, '-');
                    const isSwatch = attr.type === 'swatch';
                    return (
                      <div key={attr.name} className="mb-1.5" data-testid={`cart-item-attribute-${attrName}`}>
                        <p className="text-xs font-medium mb-1">{attr.name}:</p>
                        <div className="flex gap-1 flex-wrap">
                          {attr.items.map((option) => {
                            const optionKey = option.displayValue.toLowerCase().replace(/[^a-z0-9]+/g, '-');
                            const isSelected = item.selectedAttributes[attr.name] === option.value;
                            return (
                              <span
                                key={option.id}
                                className={`border text-xs
                                  ${isSelected ? 'border-black' : 'border-gray-300'}
                                  ${isSwatch ? 'w-5 h-5 inline-block' : 'px-1.5 py-0.5 min-w-[1.5rem] inline-flex items-center justify-center'}
                                  ${!isSwatch && isSelected ? 'bg-black text-white' : ''}
                                  ${!isSwatch && !isSelected ? 'bg-white text-black' : ''}`}
                                style={isSwatch ? { backgroundColor: option.value } : {}}
                                data-testid={`cart-item-attribute-${attrName}-${optionKey}${isSelected ? '-selected' : ''}`}
                              >
                                {!isSwatch && option.displayValue}
                              </span>
                            );
                          })}
                        </div>
                      </div>
                    );
                  })}
                </div>

                <div className="flex gap-2 items-stretch flex-shrink-0">
                  <div className="flex flex-col items-center justify-between">
                    <button
                      onClick={() => increaseQuantity(index)}
                      data-testid="cart-item-amount-increase"
                      className="w-6 h-6 border border-black flex items-center justify-center text-lg leading-none hover:bg-gray-100"
                    >
                      +
                    </button>
                    <span data-testid="cart-item-amount" className="text-sm font-medium">
                      {item.quantity}
                    </span>
                    <button
                      onClick={() => decreaseQuantity(index)}
                      data-testid="cart-item-amount-decrease"
                      className="w-6 h-6 border border-black flex items-center justify-center text-lg leading-none hover:bg-gray-100"
                    >
                      &minus;
                    </button>
                  </div>
                  <img
                    src={item.image}
                    alt={item.name}
                    className="w-20 h-full min-h-[7rem] object-cover"
                  />
                </div>
              </li>
            ))}
          </ul>
        )}

        <div className="mt-6 pt-4">
          <div className="flex justify-between items-center font-bold text-sm mb-4">
            <span>Total</span>
            <span data-testid="cart-total">${total.toFixed(2)}</span>
          </div>
          <button
            onClick={handlePlaceOrder}
            data-testid="place-order"
            disabled={cartItems.length === 0}
            className={`w-full text-white uppercase py-3 text-sm tracking-wide transition ${
              cartItems.length === 0 ? 'bg-gray-400 cursor-not-allowed' : 'bg-[#5ece7b] hover:bg-green-600'
            }`}
          >
            Place Order
          </button>
        </div>
      </div>
    </>
  );
}
